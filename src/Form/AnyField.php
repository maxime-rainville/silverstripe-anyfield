<?php declare(strict_types=1);

namespace SilverStripe\AnyField\Form;

use Behat\Mink\Element\Element;
use DNADesign\Elemental\Controllers\ElementalAreaController;
use DNADesign\Elemental\Forms\EditFormFactory;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AnyField\Services\DataObjectClassInfo;
use SilverStripe\Core\ClassInfo;
use SilverStripe\ORM\DataObject;

/**
 * Allows CMS users to edit a DataObject.
 */
class AnyField extends JsonField
{
    use AllowedClassesTrait;

    protected $schemaComponent = 'AnyField';

    public function getProps(): array
    {
        $props = parent::getProps();

        $baseClass = $this->getBaseClass();

        $allowedDataObjectClasses = $this->getAllowedDataObjectClasses();
        if (empty($allowedDataObjectClasses)) {
            throw new \InvalidArgumentException('AnyField must have at least one allowed DataObject class');
        }

        $props['allowedDataObjectClasses'] = $allowedDataObjectClasses;
        $singleton = DataObject::singleton($baseClass);
        $props['baseDataObjectName'] = $singleton->i18n_singular_name();
        $props['baseDataObjectIcon'] = $singleton->config()->get('icon');

        return $props;
    }

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


}
