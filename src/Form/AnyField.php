<?php declare(strict_types=1);

namespace SilverStripe\AnyField\Form;

use DNADesign\Elemental\Forms\EditFormFactory;
use DNADesign\Elemental\Models\BaseElement;
use Psr\Container\NotFoundExceptionInterface;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataObjectInterface;
use SilverStripe\ORM\FieldType\DBHTMLText;

/**
 * Allows CMS users to edit a DataObject.
 */
class AnyField extends JsonField
{
    use AllowedClassesTrait;

    protected $schemaComponent = 'AnyField';

    /**
     * Try to guess what class we are editing
     *
     * @param DataObjectInterface|null $record
     * @return string|null
     * @throws NotFoundExceptionInterface
     */
    protected function guessBaseClass(?DataObjectInterface $record = null): ?string
    {
        if (!$record) {
            $form = $this->getForm();

            if (!$form) {
                return null;
            }

            $record = $form->getRecord();

            if (!$record) {
                return null;
            }
        }

        $fieldName = $this->getName();

        // Elemental sometimes rename our record field to something else.
        // This bit figures out what the name is meant to be
        if (class_exists(BaseElement::class) && is_a($record, BaseElement::class)) {
            /** @var EditFormFactory $factory */
            $factory = Injector::inst()->get(EditFormFactory::class);

            // Get updated name of the form field
            $field = TextField::create($fieldName);
            $fields = FieldList::create([$field]);
            $factory->removeNamespaceFromFields($fields, ['Record' => $record]);
            $fieldName = $field->getName();
        }

        return DataObject::getSchema()->hasOneComponent($record::class, $fieldName);
    }

    public function InitialHTML(): DBHTMLText
    {
        return $this->renderWith(static::class . '_InitialHTML');
    }
}
