<?php
namespace SilverStripe\AnyField\Tests\Form;

use SilverStripe\AnyField\Form\AnyField;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormField;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\LinkField\Models\ExternalLink;
use SilverStripe\AnyField\Services\AnyService;

class AnyFieldTest extends AllowedClassesTraitTestCase
{

    protected static $extra_dataobjects = [
        AnyFieldTest\SingleLink::class,
        AnyFieldTest\SingleLinkBlock::class,
    ];

    protected static $fixture_file = '../LinkModelTest.yml';

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
        ];

        foreach ($testcases as $key => [$value, $expected]) {
            $field = $this->getAnyField();
            $field->setValue($value);

            $this->assertEquals(
                $expected,
                $field->Value(),
                "Setting AnyField value form $key works"
            );
        }
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
            'URL' => 'https://silverstripe.org',
        ]);

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


}
