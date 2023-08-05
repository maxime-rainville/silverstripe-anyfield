<?php

namespace SilverStripe\AnyField\Tests\Extensions;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\LinkField\Models\PhoneLink;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Session;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\TextField;

class ModalControllerTest extends SapphireTest
{

    protected $use_database = false;

    public function testAnyFieldForm(): void
    {
        $leftAndMain = LeftAndMain::singleton();
        $request = new HTTPRequest(
            'GET',
            'admin/methodSchema/Modals/AnyFieldForm',
            [
                'key' => PhoneLink::class,
                'data' => json_encode([
                    'ID' => 123,
                    'Title' => 'New Zealand Emergency services',
                    'Phone' => '111'
                ])
            ],
            []
        );
        $request->setSession(new Session([]));
        $leftAndMain->setRequest($request);

        /** @var Form $form */
        $form = $leftAndMain->Modals()->AnyFieldForm();

        $this->assertInstanceOf(Form::class, $form);
        $this->assertEquals('Modals/AnyFieldForm', $form->getName());

        $fields = $form->Fields();
        $this->assertField($fields, 'ID', HiddenField::class, 123);
        $this->assertField($fields, 'dataObjectClassKey', HiddenField::class, PhoneLink::class);
        $this->assertField($fields, 'Title', TextField::class, 'New Zealand Emergency services');
        $this->assertField($fields, 'Phone', TextField::class, '111');


    }

    private function assertField(FieldList $fields, string $name, string $fieldClass, $value): void
    {
        $field = $fields->dataFieldByName($name);
        $this->assertNotNull($field, "Field $name exists");
        $this->assertInstanceOf($fieldClass, $field, "Field $name is a $fieldClass");
        $this->assertEquals($value, $field->Value(), "Field $name has value $value");
    }

    public function testBadKey()
    {
        $this->expectExceptionMessage('Class I\\Dont\\Exist does not exist');

        $leftAndMain = LeftAndMain::singleton();
        $request = new HTTPRequest(
            'GET',
            'admin/methodSchema/Modals/AnyFieldForm',
            [
                'key' => 'I\\Dont\\Exist',
                'data' => json_encode([
                    'ID' => 123,
                    'Title' => 'New Zealand Emergency services',
                    'Phone' => '111'
                ])
            ],
            []
        );
        $request->setSession(new Session([]));
        $leftAndMain->setRequest($request);

        /** @var Form $form */
        $form = $leftAndMain->Modals()->AnyFieldForm();
    }

    public function testBadJson()
    {
        $this->expectExceptionMessage('Could not parse JSON data for form schema');

        $leftAndMain = LeftAndMain::singleton();
        $request = new HTTPRequest(
            'GET',
            'admin/methodSchema/Modals/AnyFieldForm',
            [
                'key' => PhoneLink::class,
                'data' => 'I am not a valid JSON string'
            ],
            []
        );
        $request->setSession(new Session([]));
        $leftAndMain->setRequest($request);

        /** @var Form $form */
        $form = $leftAndMain->Modals()->AnyFieldForm();
    }
}
