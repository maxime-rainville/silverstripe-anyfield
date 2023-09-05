<?php
namespace SilverStripe\AnyField\Tests\Form;

use League\Csv\InvalidArgument;
use SilverStripe\AnyField\Form\AnyField;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormField;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\LinkField\Models\ExternalLink;
use SilverStripe\LinkField\Models\EmailLink;
use SilverStripe\AnyField\Services\AnyService;
use SilverStripe\SiteConfig\SiteConfig;

use function PHPUnit\Framework\assertSame;

class AnyFieldTest extends AllowedClassesTraitTestCase
{

    protected $use_database = true;

    protected static $extra_dataobjects = [
        AnyFieldTest\SingleLink::class,
        AnyFieldTest\SingleLinkBlock::class,
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
        return new AnyField('AnyField');
    }

    public function testGuessBaseClass()
    {
        $field = $this->getAnyField();
        $field->setName('Link');

        $this->assertEquals(
            '',
            $field->getBaseClass(),
            'When base class is not guessable, a blank string is returned'
        );

        $form = new Form(Controller::curr(), 'Form', FieldList::create($field));
        $form->loadDataFrom(AnyFieldTest\SingleLink::create());
        $this->assertEquals(
            Link::class,
            $field->getBaseClass(),
            'When the form field is assigned to a Form with a record, the base class can be guessed'
        );

        $field->setName('PageElements_0_Link');
        $this->assertEquals(
            Link::class,
            $field->getBaseClass(AnyFieldTest\SingleLinkBlock::create()),
            'Elemental form field name can still guess the correct base class'
        );
    }

    public function testAllowedDataObjectClassesWithExplicitRecord()
    {
        $field = $this->getAnyField();
        $field->setName('Link');

        $this->assertEquals(
            AnyService::singleton()->getAllowedDataObjectClasses(
                Link::class,
                true,
                []
            ),
            $field->getAllowedDataObjectClasses(AnyFieldTest\SingleLink::create()),
            'getAllowedDataObjectClasses() relays the correct parameter to AnyService::getAllowedDataObjectClasses()'
        );
    }

    public function testSetValue()
    {
        $do = $this->objFromFixture(ExternalLink::class, 'link-1');
        $map = AnyService::singleton()->map($do);
        $testcases = [
            'DataObject' => [$do, $map],
            'Singleton' => [AnyFieldTest\SingleLink::create(), []],
            'Array' => [$map, $map],
            'JSON' => [json_encode($map), $map],
            'Empty string' => ['', null],
            'null' => [null, null],
            'Empty JSON object' => ['{}', null],
        ];

        foreach ($testcases as $key => [$value, $expected]) {
            $field = $this->getAnyField();
            $field->setValue($value);

            $this->assertEquals(
                $expected,
                $field->Value(),
                "Setting AnyField value from $key works"
            );
        }
    }

    public function testSetValueInvalidJSON()
    {
        $this->expectException(\InvalidArgumentException::class, 'Setting any field to broken JSON throws an exception');
        $field = $this->getAnyField();
        $field->setValue('{I am a broken JSON');
    }

    public function testSaveNew(): void
    {
        $field = $this->getAnyField();
        $field->setName('Link');

        $this->assertEquals(
            '',
            $field->getBaseClass(),
            'When base class is not guessable, a blank string is returned'
        );

        $form = new Form(Controller::curr(), 'Form', FieldList::create($field));
        $parentRecord = AnyFieldTest\SingleLink::create();
        $form->loadDataFrom($parentRecord);

        $field->setValue([
            'dataObjectClassKey' => ExternalLink::class,
            'Title' => 'Silverstripe CMS',
            'ExternalUrl' => 'https://silverstripe.org',
        ]);

        $field->saveInto($parentRecord);

        $link = $parentRecord->Link();
        $this->assertInstanceOf(
            ExternalLink::class,
            $link,
            'A new object has been created'
        );

        $this->assertEquals(
            'Silverstripe CMS',
            $link->Title,
            'Title for new link has been saved'
        );

        $this->assertEquals(
            'https://silverstripe.org',
            $link->ExternalUrl,
            'Data for new link has been saved'
        );
    }

    public function testUpdateExisting(): void
    {
        $field = $this->getAnyField();
        $field->setName('Link');

        $this->assertEquals(
            '',
            $field->getBaseClass(),
            'When base class is not guessable, a blank string is returned'
        );

        $form = new Form(Controller::curr(), 'Form', FieldList::create($field));
        $link = new ExternalLink();
        $link->Title = 'Silverstripe';
        $link->ExternalUrl = 'https://silverstripe.org';
        $link->write();
        $parentRecord = AnyFieldTest\SingleLink::create();
        $parentRecord->LinkID = $link->ID;
        $parentRecord->write();
        $form->loadDataFrom($parentRecord);

        $field->setValue([
            'dataObjectClassKey' => ExternalLink::class,
            'Title' => 'Silverstripe CMS',
            'ExternalUrl' => 'https://silverstripecms.org',
        ]);

        $field->saveInto($parentRecord);

        $link = $parentRecord->Link();
        $this->assertInstanceOf(
            ExternalLink::class,
            $link,
            'Type of object has not changed'
        );

        $this->assertEquals(
            'Silverstripe CMS',
            $link->Title,
            'Title for link has been updated'
        );

        $this->assertEquals(
            'https://silverstripecms.org',
            $link->ExternalUrl,
            'Data for link has been updated'
        );
    }

    public function testUpdateExistingChangeClass(): void
    {
        $field = $this->getAnyField();
        $field->setName('Link');

        $this->assertEquals(
            '',
            $field->getBaseClass(),
            'When base class is not guessable, a blank string is returned'
        );

        $form = new Form(Controller::curr(), 'Form', FieldList::create($field));
        $link = new ExternalLink();
        $link->Title = 'Silverstripe';
        $link->ExternalUrl = 'https://silverstripe.org';
        $link->write();
        $initialLinkID = $link->ID;
        $parentRecord = AnyFieldTest\SingleLink::create();
        $parentRecord->LinkID = $link->ID;
        $parentRecord->write();
        $form->loadDataFrom($parentRecord);

        $field->setValue([
            'dataObjectClassKey' => EmailLink::class,
            'Title' => 'Silverstripe email address',
            'Email' => 'hello@silverstripe.org',
        ]);

        $field->saveInto($parentRecord);

        $link = $parentRecord->Link();
        $this->assertInstanceOf(
            EmailLink::class,
            $link,
            'Type of object has changed'
        );

        $this->assertEquals(
            $initialLinkID,
            $link->ID,
            'ID of object has not changed'
        );

        $this->assertEquals(
            'Silverstripe email address',
            $link->Title,
            'Title for link has been updated'
        );

        $this->assertEquals(
            'hello@silverstripe.org',
            $link->Email,
            'Data for link has been updated'
        );
    }

    public function testClearingExisting(): void
    {
        $field = $this->getAnyField();
        $field->setName('Link');

        $this->assertEquals(
            '',
            $field->getBaseClass(),
            'When base class is not guessable, a blank string is returned'
        );

        $form = new Form(Controller::curr(), 'Form', FieldList::create($field));
        $link = new ExternalLink();
        $link->Title = 'Silverstripe';
        $link->ExternalUrl = 'https://silverstripe.org';
        $link->write();
        $initialLinkID = $link->ID;
        $parentRecord = AnyFieldTest\SingleLink::create();
        $parentRecord->LinkID = $link->ID;
        $parentRecord->write();
        $form->loadDataFrom($parentRecord);

        $field->setValue('{}');

        $field->saveInto($parentRecord);

        $this->assertEmpty(
            $parentRecord->LinkID,
            'Link should no longer be associated to parent record'
        );

        $this->assertEmpty(
            Link::get()->byID($initialLinkID),
            'Link should have been deleted'
        );
    }

    public function testCanCreatePermission()
    {
        $this->expectExceptionCode(403, 'AnyField respects can create permission');
        Link::singleton()->forcePermission('create', false);

        $field = $this->getAnyField();
        $field->setName('Link');

        $this->assertEquals(
            '',
            $field->getBaseClass(),
            'When base class is not guessable, a blank string is returned'
        );

        $form = new Form(Controller::curr(), 'Form', FieldList::create($field));
        $parentRecord = AnyFieldTest\SingleLink::create();
        $form->loadDataFrom($parentRecord);

        $field->setValue([
            'dataObjectClassKey' => ExternalLink::class,
            'Title' => 'Silverstripe CMS',
            'ExternalUrl' => 'https://silverstripe.org',
        ]);

        $field->saveInto($parentRecord);
    }

    public function testCanCreateWithInvalidClass()
    {
        $this->expectException(
            \InvalidArgumentException::class,
            'AnyField is constrained by its allowed classes'
        );

        $field = $this->getAnyField();
        $field->setName('Link');

        $form = new Form(Controller::curr(), 'Form', FieldList::create($field));
        $parentRecord = AnyFieldTest\SingleLink::create();
        $form->loadDataFrom($parentRecord);

        $field->setValue([
            'dataObjectClassKey' => SiteConfig::class,
            'Title' => 'Silverstripe CMS',
        ]);

        $field->saveInto($parentRecord);
    }

    public function testCanUpdatePermission()
    {
        $this->expectExceptionCode(403, 'AnyField respects can edit permission');
        Link::singleton()->forcePermission('update', false);

        $field = $this->getAnyField();
        $field->setName('Link');

        $form = new Form(Controller::curr(), 'Form', FieldList::create($field));
        $link = new ExternalLink();
        $link->Title = 'Silverstripe';
        $link->ExternalUrl = 'https://silverstripe.org';
        $link->write();
        $parentRecord = AnyFieldTest\SingleLink::create();
        $parentRecord->LinkID = $link->ID;
        $parentRecord->write();
        $form->loadDataFrom($parentRecord);

        $field->setValue([
            'dataObjectClassKey' => ExternalLink::class,
            'Title' => 'Silverstripe CMS',
            'ExternalUrl' => 'https://silverstripecms.org',
        ]);

        $field->saveInto($parentRecord);
    }

    public function testUpdateWithInvalidClass(): void
    {
        $this->expectException(
            \InvalidArgumentException::class,
            'AnyField is constrained by its allowed classes'
        );

        $field = $this->getAnyField();
        $field->setName('Link');
        $field->addExcludedClass(EmailLink::class);

        $form = new Form(Controller::curr(), 'Form', FieldList::create($field));
        $link = new ExternalLink();
        $link->Title = 'Silverstripe';
        $link->ExternalUrl = 'https://silverstripe.org';
        $link->write();
        $initialLinkID = $link->ID;
        $parentRecord = AnyFieldTest\SingleLink::create();
        $parentRecord->LinkID = $link->ID;
        $parentRecord->write();
        $form->loadDataFrom($parentRecord);

        $field->setValue([
            'dataObjectClassKey' => EmailLink::class,
            'Title' => 'Silverstripe email address',
            'Email' => 'hello@silverstripe.org',
        ]);

        $field->saveInto($parentRecord);
    }

    public function testCanDeletePermission()
    {
        $this->expectExceptionCode(403, 'AnyField respects can delete permission');
        Link::singleton()->forcePermission('delete', false);

        $field = $this->getAnyField();
        $field->setName('Link');

        $form = new Form(Controller::curr(), 'Form', FieldList::create($field));
        $link = new ExternalLink();
        $link->Title = 'Silverstripe';
        $link->ExternalUrl = 'https://silverstripe.org';
        $link->write();
        $initialLinkID = $link->ID;
        $parentRecord = AnyFieldTest\SingleLink::create();
        $parentRecord->LinkID = $link->ID;
        $parentRecord->write();
        $form->loadDataFrom($parentRecord);

        $field->setValue('{}');

        $field->saveInto($parentRecord);
    }

    public function testInitialHTML()
    {
        $field = $this->getAnyField();
        $field->setName('Link');

        $this->assertStringContainsString(
            '<div class="any-field-box form-control any-picker"></div>',
            $field->InitialHTML()->forTemplate(),
            'Some HTML is preloaded in the field'
        );
    }

    public function testComponent()
    {
        $field = $this->getAnyField();

        $this->assertEquals(
            'AnyField',
            $field->getSchemaComponent(),
            'Schema component is set to AnyField'
        );
    }

    public function testInputValue()
    {
        $field = $this->getAnyField();

        $this->assertEmpty($field->InputValue(), 'InputValue return blank when no value is provided');

        $expected = [
            'dataObjectClassKey' => ExternalLink::class,
            'Title' => 'Silverstripe',
            'ExternalUrl' => 'https://silverstripe.org',
        ];

        $link = new ExternalLink();
        $link->Title = 'Silverstripe';
        $link->ExternalUrl = 'https://silverstripe.org';
        $link->write();
        $field->setValue($link);
        $this->assertPartialMatch(
            $expected,
            json_decode($field->InputValue(), true),
            'InputValue can convert dataObject to JSON'
        );

        $field->setValue($expected);
        $this->assertPartialMatch(
            $expected,
            json_decode($field->InputValue(), true),
            'InputValue can convert array to JSON'
        );
    }

    private function assertPartialMatch($expected, $actual, $message)
    {
        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $actual, $message);
            $this->assertEquals($value, $actual[$key], $message);
        }
    }
}
