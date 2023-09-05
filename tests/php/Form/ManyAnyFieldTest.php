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
use SilverStripe\SiteConfig\SiteConfig;

class ManyAnyFieldTest extends AllowedClassesTraitTestCase
{

    protected static $extra_dataobjects = [
        ManyAnyFieldTest\ManyLink::class,
        LinkOwner::class,
    ];

    protected static $required_extensions = [
        Link::class => [
            AnyFieldTest\ForcePermissionExtension::class,
        ],
    ];

    protected static $fixture_file = '../LinkModelTest.yml';

    protected function tearDown(): void
    {
        Link::singleton()->resetForcePermission();
        parent::tearDown();
    }

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

    public function testSetValueWithListInConstructor()
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

        $this->assertCount(2, $owner->Links(), 'My owner should only have one link');

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
        $expectedValue =  AnyService::singleton()->mapList($owner->Links());
        $field = new ManyAnyField('Links');
        $field->setValue(json_encode($expectedValue));

        $this->assertEquals(
            $expectedValue,
            $field->Value(),
            'When the value is explicitly set to a JSON string, we don\'t read it from the data list'
        );
    }

    public function testSetValueWithEmptyJSONString()
    {
        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $field = new ManyAnyField('Links');
        $field->setValue('[]', $owner);

        $this->assertEquals(
            [],
            $field->Value(),
            'When the value is explicitly set to empty JSON string, we get an empty list'
        );
    }

    public function testSetValueWithExplicitList()
    {
        $links = Link::get();
        $field = new ManyAnyField('Test', 'Test');
        $field->setValue($links);

        $expectedValue =  AnyService::singleton()->mapList($links);

        $this->assertEquals(
            $expectedValue,
            $field->Value(),
            'Value should be deduct from the list when no other data is provided'
        );
    }

    public function testSaveInto()
    {
        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $externalLinkID = $this->idFromFixture(ExternalLink::class, 'link-2');
        $emailLinkID = $this->idFromFixture(EmailLink::class, 'link-3');

        $this->assertEquals(
            [
                $externalLinkID => 'Link2',
                $emailLinkID => 'Link3'
            ],
            $owner->Links()->map()->toArray(),
            'Initial link list contains 2 elements'
        );

        $field = new ManyAnyField('Links', 'Links', $owner->Links());
        $submittedData = [
            [
                'ID' => $externalLinkID,
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
        $this->assertEquals($externalLinkID, $links[0]->ID, 'The first link should still have the same ID');
        $this->assertEquals('http://www.google.co.nz', $links[0]->ExternalUrl, 'The first link URL should have been updated');

        $this->assertEquals('My new email address', $links[1]->Title, 'The second link has the expected title');
        $this->assertNotEquals('aebc8afd-7fbc-4503-bc8f-3fd459a3f2de', $links[1]->ID, 'The second link should have a proper ID');
        $this->assertEquals('maxime@example.com', $links[1]->Email, 'The first link URL should have been updated');

        $this->assertEmpty(
            Link::get()->filter('ID', $emailLinkID),
            'Email link has been deleted because it was not in the submitted data'
        );
    }

    public function testCanCreatePermission()
    {
        $this->expectExceptionCode(403, 'ManyAnyField respects can create permission');
        Link::singleton()->forcePermission('create', false);

        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $data = AnyService::singleton()->mapList($owner->Links());

        $field = new ManyAnyField('Links', 'Links', $owner->Links());
        $data[] = [
            'Title' => 'My new email address',
            'OpenInNew' => 1,
            'Email' => 'maxime@example.com',
            'ID' => 'aebc8afd-7fbc-4503-bc8f-3fd459a3f2de',
            'dataObjectClassKey' => EmailLink::class,
            'isNew' => true
        ];
        $field->setBaseClass(Link::class);
        $field->setValue(json_encode($data), $owner);
        $field->saveInto($owner);
    }

    public function testCanCreateWithInvalidClass()
    {
        $this->expectException(
            \InvalidArgumentException::class,
            'AnyField is constrained by its allowed classes'
        );

        $field = $this->getAnyField();
        $field->setName('Links');

        $form = new Form(Controller::curr(), 'Form', FieldList::create($field));
        $parentRecord = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $form->loadDataFrom($parentRecord);

        $data = AnyService::singleton()->mapList($parentRecord->Links());
        $data[] = [
            'dataObjectClassKey' => SiteConfig::class,
            'Title' => 'Silverstripe CMS',
        ];

        $field->setValue($data);

        $field->saveInto($parentRecord);
    }

    public function testCanUpdatePermission()
    {
        $this->expectExceptionCode(403, 'ManyAnyField respects can update permission');
        Link::singleton()->forcePermission('update', false);

        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $data = AnyService::singleton()->mapList($owner->Links());

        $field = new ManyAnyField('Links', 'Links', $owner->Links());
        $data[0]['Title'] = 'My new title';
        $field->setBaseClass(Link::class);
        $field->setValue(json_encode($data), $owner);
        $field->saveInto($owner);
    }

    public function testCanDeletePermission()
    {
        $this->expectExceptionCode(403, 'ManyAnyField respects can delete permission');
        Link::singleton()->forcePermission('delete', false);

        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $data = AnyService::singleton()->mapList($owner->Links());

        $field = new ManyAnyField('Links', 'Links', $owner->Links());
        unset($data[0]);
        $field->setBaseClass(Link::class);
        $field->setValue(json_encode($data), $owner);
        $field->saveInto($owner);
    }

    public function testInputValue()
    {
        $field = $this->getAnyField();

        $this->assertEmpty($field->InputValue(), 'InputValue return blank when no value is provided');

        $owner = $this->objFromFixture(LinkOwner::class, 'link-owner-1');
        $expected = AnyService::singleton()->mapList($owner->Links());

        $field->setValue($owner->Links());
        $this->assertEquals(
            $expected,
            json_decode($field->InputValue(), true),
            'InputValue can convert DataList to JSON'
        );

        $field->setValue($expected);
        $this->assertEquals(
            $expected,
            json_decode($field->InputValue(), true),
            'InputValue can convert array to JSON'
        );
    }
}
