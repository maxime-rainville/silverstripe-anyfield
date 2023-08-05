<?php declare(strict_types=1);

namespace SilverStripe\AnyField\Form;

use InvalidArgumentException;
use LogicException;
use MaximeRainville\SilverstripeReact\ReactFormField;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\FormField;
use SilverStripe\AnyField\JsonData;
use SilverStripe\AnyField\Services\AnyService;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataObjectInterface;

/**
 * Field designed to edit complex data passed as a JSON string. Other FormFields can be built on top of this one.
 *
 * It will output a hidden input with serialize JSON Data.
 */
abstract class JsonField extends ReactFormField
{
    protected $schemaDataType = FormField::SCHEMA_DATA_TYPE_CUSTOM;
    protected $renderInput = true;
    protected $inputType = 'hidden';

    public function setValue($value, $data = null)
    {
        if ($value && $value instanceof DataObject) {
            if ($value->isInDB()) {
                $value = AnyService::singleton()->map($value);
            } else {
                // We've been provided a singleton which means there's not object yet
                $value = [];
            }
        } elseif (is_string($value)) {
            // We've been provided a JSON string
            $value = $this->parseString($value);
        }

        return parent::setValue($value, $data);
    }

    /**
     * @param DataObject|DataObjectInterface $record
     * @return $this
     */
    public function saveInto(DataObjectInterface $record)
    {
        // Check required relation details are available
        $fieldname = $this->getName();

        if (!$fieldname) {
            throw new LogicException(sprintf('%s: Field must have a name', static::class));
        }

        $service = AnyService::singleton();
        $value = $this->dataValue();

        if ($class = DataObject::getSchema()->hasOneComponent(get_class($record), $fieldname)) {
            /** @var JsonData|DataObject $dataObject */
            $dataObjectID = $record->{"{$fieldname}ID"};

            if ($dataObjectID && $dataObject = $record->$fieldname) {
                // There's already an object attached to the record
                if ($value) {
                    // We are updating the value
                    if (!$dataObject->canEdit()) {
                        Controller::curr()->httpError(403);
                    }
                    $dataObject = $service->setData($dataObject, $value);
                    $this->validClassName($dataObject->ClassName, $record);
                    $dataObject->write();
                    $record->{"{$fieldname}ID"} = $dataObject->ID;
                } else {
                    if (!$dataObject->canDelete()) {
                        Controller::curr()->httpError(403);
                    }
                    // We are deleting the value
                    $dataObject->delete();
                    $record->{"{$fieldname}ID"} = 0;
                }
            } elseif ($value) {
                // There's no pre-existing object so we have to create a new one.
                $dataObject = Injector::inst()->create($class);
                $dataObject = $service->setData($dataObject, $value);
                if (!$dataObject->canCreate()) {
                    Controller::curr()->httpError(403);
                }
                $this->validClassName($dataObject->ClassName, $record);
                $dataObject->write();
                $record->{"{$fieldname}ID"} = $dataObject->ID;
            } else {
                // There's no pre-existing object and no value to create one. The field is being left blank.
            }
        }

        return $this;
    }

    protected function parseString(string $value): ?array
    {
        if (!$value) {
            return null;
        }

        $data = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s: Could not parse provided JSON string. Failed with "%s"',
                    static::class,
                    json_last_error_msg()
                )
            );
        }

        if (!is_array($data) && empty($data)) {
            return null;
        }

        return $data;
    }

    public function getComponent(): string
    {
        return $this->schemaComponent;
    }

    public function InputValue(): string
    {
        $value = $this->Value();

        if ($value instanceof DataObject) {
            $value = AnyService::singleton()->map($value);
        }

        if (is_array($value)) {
            $value = json_encode($value);
        }

        return $value;
    }

    /**
     * Check if the class name is valid for this field.
     *
     * Should throw an exception if the class name is not valid.
     */
    abstract protected function validClassName(string $className, ?DataObjectInterface $record): void;
}
