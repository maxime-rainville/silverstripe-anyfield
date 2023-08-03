<?php
namespace SilverStripe\AnyField\Tests\Form;

use SilverStripe\AnyField\Form\ManyAnyField;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormField;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\AnyField\Tests\LinkOwner;
use SilverStripe\LinkField\Models\ExternalLink;
use SilverStripe\LinkField\Models\EmailLink;
use SilverStripe\AnyField\Services\AnyService;

class ManyAnyFieldTest extends AllowedClassesTraitTestCase
{

    protected static $extra_dataobjects = [
        ManyAnyFieldTest\ManyLink::class,
        LinkOwner::class,
    ];

    protected static $fixture_file = '../LinkModelTest.yml';

    protected function getAnyField(): FormField
    {
        return new ManyAnyField('AnyField');
    }

    public function testGuessBaseClass()
    {
        $field = $this->getAnyField();
        $field->setName('Links');

        $this->assertEquals(
            '',
            $field->getBaseClass(),
            'When base class is not guessable, a blank string is returned'
        );

        $form = new Form(Controller::curr(), 'Form', FieldList::create($field));
        $form->loadDataFrom(ManyAnyFieldTest\ManyLink::create());
        $this->assertEquals(
            Link::class,
            $field->getBaseClass(),
            'When the form field is assigned to a Form with a record, the base class can be guessed'
        );
    }

    public function testList()
    {
        $field = new ManyAnyField('Test');
        $links = Link::get();
        $field->setList($links);
        $this->assertEquals($links, $field->getList(), 'setList should change the value returned by getList');
    }

    public function testSetValueWithExplicitList()
    {
        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $links = Link::get();
        $field = new ManyAnyField('Test', 'Test', $links);
        $field->setValue(null, $owner);

        $expectedValue =  AnyService::singleton()->mapList($links);

        $this->assertEquals(
            $expectedValue,
            $field->Value(),
            'Value should be deduct from the list when no other data is provided'
        );
    }

    public function testSetValueWithImplicitList()
    {
        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $field = new ManyAnyField('Links');
        $field->setValue(null, $owner);

        $this->assertCount(1, $owner->Links(), 'My owner should only have one link');

        $expectedValue =  AnyService::singleton()->mapList($owner->Links());

        $this->assertEquals(
            $expectedValue,
            $field->Value(),
            'Value should be deduct from the list matching the field name when the list is not explicitly set'
        );
    }

    public function testSetValueWithJSONString()
    {
        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $field = new ManyAnyField('Links');
        $field->setValue('[]', $owner);

        $this->assertEquals(
            [],
            $field->Value(),
            'When the value is explicitly set to a JSON string, when don\'t to read it from the data list'
        );
    }


    public function testSaveInto()
    {
        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $linkID = $this->idFromFixture(ExternalLink::class, 'link-2');

        $field = new ManyAnyField('Links');
        $submittedData = [
            [
                'ID' => $linkID,
                'Title' => 'My update link',
                'ExternalUrl' => 'http://www.google.co.nz',
                'dataObjectClassKey' => ExternalLink::class,
            ],
            [
                'Title' => 'My new email address',
                'OpenInNew' => 1,
                'Email' => 'maxime@example.com',
                'ID' => 'aebc8afd-7fbc-4503-bc8f-3fd459a3f2de',
                'dataObjectClassKey' => EmailLink::class,
                'isNew' => true
            ]
        ];
        $field->setBaseClass(Link::class);
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
