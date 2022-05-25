<?php

namespace SilverStripe\LinkField\Tests\Form;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\LinkField\Models\ExternalLink;
use SilverStripe\LinkField\Form\MultiLinkField;
use SilverStripe\LinkField\Tests\LinkOwner;

class MultiLinkFieldTest extends SapphireTest
{

    protected static $fixture_file = '../LinkModelTest.yml';

    public static $extra_data_objects = [
        LinkOwner::class,
    ];

    public function testList()
    {
        $field = new MultiLinkField('Test');
        $links = Link::get();
        $field->setList($links);
        $this->assertEquals($links, $field->getList(), 'setList should change the value returned by getList');
    }

    public function testSetValueWithExplicitList()
    {
        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $links = Link::get();
        $field = new MultiLinkField('Test', 'Test', $links);
        $field->setValue(null, $owner);

        $expectedValue = json_encode(
            array_map(function (Link $link) {
                return $link->jsonSerialize();
            }, $links->toArray())
        );

        $this->assertEquals(
            $expectedValue,
            $field->Value(),
            'Value should be deduct from the list when no other data is provided'
        );
    }

    public function testSetValueWithImplicitList()
    {
        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $field = new MultiLinkField('Links');
        $field->setValue(null, $owner);

        $this->assertCount(1, $owner->Links(), 'My owner should only have one link');

        $expectedValue = json_encode(
            array_map(function (Link $link) {
                return $link->jsonSerialize();
            }, $owner->Links()->toArray())
        );

        $this->assertEquals(
            $expectedValue,
            $field->Value(),
            'Value should be deduct from the list matching the field name when the list is not explicitly set'
        );
    }

    public function testSetValueWithJSONString()
    {
        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $field = new MultiLinkField('Links');
        $field->setValue('[]', $owner);

        $this->assertEquals(
            '[]',
            $field->Value(),
            'When the value is explicitly set to a JSON string, when don\'t to read it from the data list'
        );
    }


    public function testSaveInto()
    {
        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $linkID = $this->idFromFixture(ExternalLink::class, 'link-2');

        $field = new MultiLinkField('Links');
        $submittedData = [
            [
                'ID' => $linkID,
                'Title' => 'My update link',
                'ExternalUrl' => 'http://www.google.co.nz',
                'typeKey' => 'external',
            ],
            [
                'Title' => 'My new email address',
                'OpenInNew' => 1,
                'Email' => 'maxime@example.com',
                'ID' => 'aebc8afd-7fbc-4503-bc8f-3fd459a3f2de',
                'typeKey' => 'email',
                'isNew' => true
            ]
        ];
        $field->setValue(json_encode($submittedData), $owner);
        $field->saveInto($owner);
        $owner->write();

        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $links = $owner->Links()->toArray();

        $this->assertCount(2, $links, 'There should be two links');

        $this->assertEquals('My update link', $links[0]->Title, 'The first link should have an updated title');
        $this->assertEquals($linkID, $links[0]->ID, 'The first link should still have the same ID');
        $this->assertEquals('http://www.google.co.nz', $links[0]->ExternalUrl, 'The first link URL should have been updated');

        $this->assertEquals('My new email address', $links[1]->Title, 'The second link has the expected title');
        $this->assertNotEquals('aebc8afd-7fbc-4503-bc8f-3fd459a3f2de', $links[1]->ID, 'The second link should have a proper ID');
        $this->assertEquals('maxime@example.com', $links[1]->Email, 'The first link URL should have been updated');
    }
}
