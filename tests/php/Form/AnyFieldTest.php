<?php
namespace SilverStripe\AnyField\Tests\Form;

use SilverStripe\AnyField\Form\AnyField;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormField;
use SilverStripe\LinkField\Models\Link;

class AnyFieldTest extends AllowedClassesTraitTest
{

    protected static $extra_dataobjects = [
        AnyFieldTest\SingleLink::class,
    ];

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
    }
}
