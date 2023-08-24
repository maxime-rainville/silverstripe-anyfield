<?php declare(strict_types=1);

namespace SilverStripe\AnyField\Form;

use DNADesign\Elemental\Controllers\ElementalAreaController;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataObjectInterface;

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
    protected function guessBaseClass(?DataObjectInterface $record = null): ?string
    {
        if (empty($record)) {
            $form = $this->getForm();
            if (!$form) {
                return null;
            }

            $record = $this->getForm()->getRecord();
            if (!$record) {
                return null;
            }
        }

        $fieldname = $this->getName();
        $class = DataObject::getSchema()->hasOneComponent(get_class($record), $fieldname);

        // Elemental sometimes rename our record field to something else.
        // This bit figures out what the name is meant to be
        if (empty($class) && $record instanceof BaseElement) {
            $fakeData = ElementalAreaController::removeNamespacesFromFields([$fieldname => 0], $record->ID);
            if (empty($fakeData)) {
                return null;
            }
            $fakeData = array_flip($fakeData);
            $fieldname = $fakeData[0];
            $class = DataObject::getSchema()->hasOneComponent(get_class($record), $fieldname);
        };

        return $class;
    }

    public function InitialHTML()
    {
        return $this->renderWith(static::class . '_InitialHTML');
    }
}
