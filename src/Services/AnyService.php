<?php

namespace SilverStripe\AnyField\Services;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Injector\InjectorNotFoundException;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\HiddenField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\SS_List;

/**
 * Service for managing the class definitions for the AnyField.
 */
class AnyService
{
    use Injectable;

    /**
     * Generate the Any Field definition for a given DataObject class.
     * @throws \InjectorNotFoundException
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
     */
    public function generateDescription(string $className, array $data): array
    {
        $dummy = Injector::inst()->create($className, $data, DataObject::CREATE_MEMORY_HYDRATED);
        $this->instanceOfDataObject($dummy);
        $summary = $dummy->hasMethod('getSummary') ? (string)$dummy->getSummary() : '';

        if (empty($summary) && $dummy->hasMethod('getDescription')) {
            $summary = (string)$dummy->getDescription();
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
        $fieldlist = $record->getCMSFields();
        $fieldlist->add(HiddenField::create('ID'));
        $form = Form::create(null, null, $fieldlist, FieldList::create());
        $form->loadDataFrom($record);
        $data = $form->getData();
        $data['dataObjectClassKey'] = $record->ClassName;
        return $data;
    }

    private function instanceOfDataObject(mixed $d): void
    {
        if (!$d instanceof DataObject) {
            $classname = get_class($d);
            throw new InjectorNotFoundException("The '{$classname}' is not a valid DataObject");
        }
    }

    /**
     * Given a List of DataObject, return a map of its fields so it can be edited in a AnyField
     */
    public function mapList(SS_List $list): array
    {
        return array_map([$this, 'map'], $list->toArray());
    }

    /**
     *
     */
    public function jsonSerialize(DataObject $value): string
    {
        $data = $this->map($value);
        return json_encode($data, JSON_FORCE_OBJECT);
    }

    public function jsonSerializeList(SS_List $list): string
    {
        return json_encode($$this->mapList($list));
    }

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

        $fieldlist = $record->getCMSFields();
        $form = Form::create(null, null, $fieldlist, FieldList::create());
        $form->loadDataFrom($data);
        $form->saveInto($record);

        // foreach ($data as $key => $value) {
        //     if ($key !== 'ID' && $record->hasField($key)) {
        //         $record->setField($key, $value);
        //     }
        // }

        return $record;
    }

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
