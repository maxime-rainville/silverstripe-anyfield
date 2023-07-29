<?php
namespace SilverStripe\AnyField\Tests\Form;

use SilverStripe\AnyField\Form\ManyAnyField;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormField;
use SilverStripe\LinkField\Models\Link;

class ManyAnyFieldTest extends AllowedClassesTraitTest
{

    protected static $extra_dataobjects = [
        ManyAnyFieldTest\ManyLink::class,
    ];

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
}
