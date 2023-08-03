<?php declare(strict_types=1);

namespace SilverStripe\AnyField\Form;

use Behat\Mink\Element\Element;
use DNADesign\Elemental\Controllers\ElementalAreaController;
use DNADesign\Elemental\Forms\EditFormFactory;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AnyField\Services\AnyService;
use SilverStripe\Core\ClassInfo;
use SilverStripe\ORM\DataObject;

/**
 * Allows CMS users to edit a DataObject.
 */
class AnyField extends JsonField
{
    use AllowedClassesTrait;

    protected $schemaComponent = 'AnyField';

    /**
     * Try to guess what class we are editing
     */
    private function guessBaseClass(): ?string
    {
        $form = $this->getForm();
        if (!$form) {
            return null;
        }

        $record = $this->getForm()->getRecord();
        if (!$record) {
            return null;
        }

        $fieldname = $this->getName();

        // The name of Elemental block fields are rename with a prefix.
        if ($record instanceof BaseElement) {
            $fakeData = ElementalAreaController::removeNamespacesFromFields([$fieldname => 0], $record->ID);
            $fakeData = array_flip($fakeData);
            $fieldname = $fakeData[0];
        };

        $class = DataObject::getSchema()->hasOneComponent(get_class($record), $fieldname);
        return $class;
    }

    public function InitialHTML()
    {
        return $this->renderWith(static::class . '_InitialHTML');
    }
}
