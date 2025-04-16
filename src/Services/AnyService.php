<?php

namespace SilverStripe\AnyField\Services;

use ReflectionException;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Injector\InjectorNotFoundException;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Model\List\SS_List;
use SilverStripe\ORM\DataObject;

/**
 * Service for managing the class definitions for the AnyField.
 */
class AnyService
{
    use Injectable;

    /**
     * Generate the Any Field definition for a given DataObject class.
     *
     * @param string $className
     * @return array
     * @throws InjectorNotFoundException
     */
    public function generateFieldDefinition(string $className): array
    {
        $singleton = DataObject::singleton($className);
        $this->instanceOfDataObject($singleton);

        return [
            'key' => $className,
            'title' => $singleton->i18n_singular_name(),
            'icon' => $singleton->config()->get('icon'),
            'modalHandler' => $singleton->config()->get('modal_handler'),
        ];
    }

    /**
     * Generate the Any Field description for a given DataObject class.
     *
     * @param string $className
     * @param array $data
     * @return array
     * @throws InjectorNotFoundException
     */
    public function generateDescription(string $className, array $data): array
    {
        $dummy = Injector::inst()->create($className, $data, DataObject::CREATE_MEMORY_HYDRATED);
        $this->instanceOfDataObject($dummy);
        $summary = $dummy->hasMethod('getSummary') ? (string) $dummy->getSummary() : '';

        if (!$summary && $dummy->hasMethod('getDescription')) {
            $summary = (string) $dummy->getDescription();
        }

        return [
            'title' => $dummy->getTitle(),
            'description' => $summary,
        ];
    }

    /**
     * Given a DataObject, return a map of its fields so it can be edited in a AnyField
     */
    public function map(DataObject $record): array
    {
        $idField = HiddenField::create('ID');
        $fieldList = $record->getCMSFields();
        $fieldList->add($idField);
        $form = Form::create(null, null, $fieldList, FieldList::create());
        $form->loadDataFrom($record);
        $data = $form->getData();
        $data['dataObjectClassKey'] = $record->ClassName;

        return $data;
    }

    /**
     * @param mixed $dataObject
     * @return void
     * @throws InjectorNotFoundException
     */
    private function instanceOfDataObject(mixed $dataObject): void
    {
        if (!$dataObject instanceof DataObject) {
            $message  = sprintf('The "%" is not a valid DataObject', $dataObject::class);

            throw new InjectorNotFoundException($message);
        }
    }

    /**
     * Given a List of DataObject, return a map of its fields so it can be edited in a AnyField
     */
    public function mapList(SS_List $list): array
    {
        return array_map([$this, 'map'], $list->toArray());
    }

    public function jsonSerialize(DataObject $value): string
    {
        $data = $this->map($value);

        return json_encode($data, JSON_FORCE_OBJECT);
    }

    public function jsonSerializeList(SS_List $list): string
    {
        return json_encode($this->mapList($list));
    }

    /**
     * @param DataObject $record
     * @param array $data
     * @return DataObject
     * @throws InjectorNotFoundException
     */
    public function setData(DataObject $record, array $data): DataObject
    {
        $dataObjectClassKey = $data['dataObjectClassKey'] ?? null;

        $this->instanceOfDataObject(DataObject::singleton($dataObjectClassKey));

        // Check if we want to change the type of our underlying data object
        if ($record->ClassName !== $dataObjectClassKey) {
            if ($record->isInDB()) {
                $record = $record->newClassInstance($dataObjectClassKey);
            } else {
                $record = Injector::inst()->create($dataObjectClassKey);
            }
        }

        $fieldList = $record->getCMSFields();
        $form = Form::create(null, null, $fieldList, FieldList::create());
        $form->loadDataFrom($data);
        $form->saveInto($record);

        return $record;
    }

    /**
     * @param string $baseClass
     * @param bool $recursivelyAddChildClass
     * @param array $excludedClasses
     * @return array
     * @throws InjectorNotFoundException
     * @throws ReflectionException
     */
    public function getAllowedDataObjectClasses(
        string $baseClass,
        bool $recursivelyAddChildClass,
        array $excludedClasses
    ): array {
        $singleton = DataObject::singleton($baseClass);
        $this->instanceOfDataObject($singleton);

        $allowedDataObjectClasses = [];

        if (!$recursivelyAddChildClass) {
            $allowedDataObjectClasses[$baseClass] = $this->generateFieldDefinition($baseClass);
        } else {
            $classes = ClassInfo::subclassesFor($baseClass);

            foreach ($classes as $class) {
                if (in_array($class, $excludedClasses)) {
                    continue;
                }
                $allowedDataObjectClasses[$class] = $this->generateFieldDefinition($class);
            }
        }

        return $allowedDataObjectClasses;
    }
}
