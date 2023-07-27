<?php

namespace SilverStripe\AnyField\Services;

use InvalidArgumentException;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\SS_List;

class DataObjectClassInfo
{
    use Injectable;

    public static function generateFieldDefinition(string $className): array
    {
        $singleton = DataObject::singleton($className);
        return [
            'key' => $className,
            'title' => $singleton->i18n_singular_name(),
            'icon' => $singleton->config()->get('icon'),
            'modalHandler' => $singleton->config()->get('modal_handler'),
        ];
    }

    public function generateDescription(string $className, array $data): array
    {
        $dummy = Injector::inst()->create($className, $data, DataObject::CREATE_MEMORY_HYDRATED);

        $summary = $dummy->hasMethod('getSummary') ? (string)$dummy->getSummary() : '';

        return [
            'title' => $dummy->getTitle(),
            'description' => $summary,
        ];
    }

    private function map(DataObject $value): array
    {
        $data = $value->toMap();
        $data['dataObjectClassKey'] = $value->ClassName;
        return $data;
    }

    public function jsonSerialize(DataObject $value): string
    {
        $data = $this->map($value);
        return json_encode($data, JSON_FORCE_OBJECT);
    }

    public function jsonSerializeList(SS_List $list): string
    {
        $data = array_map([$this, 'map'], $list->toArray());
        return json_encode($data);
    }

    public function setData(DataObject $record, array|string $data): DataObject
    {
        if (is_string($data)) {
            $data = json_decode($data, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidArgumentException(sprintf(
                    '%s: Decoding json string failed with "%s"',
                    static::class,
                    json_last_error_msg()
                ));
            }
        }
        //  elseif ($data instanceof JsonData) {
        //     $data = $data->jsonSerialize();
        // }

        if (!is_array($data)) {
            throw new InvalidArgumentException(sprintf('%s: Could not convert $data to an array.', static::class));
        }

        $dataObjectClassKey = $data['dataObjectClassKey'] ?? null;

        if (!$dataObjectClassKey) {
            throw new InvalidArgumentException(sprintf('%s: $data does not have a dataObjectClassKey.', static::class));
        }

        // Check if we want to change the type of our underlying data object
        if ($record->ClassName !== $dataObjectClassKey) {
            if ($record->isInDB()) {
                $record = $record->newClassInstance($dataObjectClassKey);
            } else {
                $record = Injector::inst()->create($dataObjectClassKey);
            }
        }

        foreach ($data as $key => $value) {
            if ($key !== 'ID' && $record->hasField($key)) {
                $record->setField($key, $value);
            }
        }

        return $record;
    }

    public function getAllowedDataObjectClasses(string $baseClass, bool $recursivelyAddChildClass, array $excludedClasses): array
    {
        $singleton = DataObject::singleton($baseClass);

        if (!$singleton) {
            throw new \InvalidArgumentException($baseClass . ' is not a valid DataObject class and cannot be managed by an AnyField');
        }

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
