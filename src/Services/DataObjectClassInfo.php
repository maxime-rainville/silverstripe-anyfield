<?php

namespace SilverStripe\AnyField\Services;

use InvalidArgumentException;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataObject;

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

        return [
            'title' => $dummy->getTitle(),
            'description' => 'booya',
        ];
    }

    public function jsonSerialize(DataObject $value): string
    {
        $data = $value->toMap();
        $data['dataObjectClassKey'] = $value->ClassName;

        return json_encode($data, JSON_FORCE_OBJECT);
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
}
