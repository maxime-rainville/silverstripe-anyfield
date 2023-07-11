<?php

namespace SilverStripe\LinkField\ORM;

use SilverStripe\Core\Config\Config;
use SilverStripe\LinkField\JsonData;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBField;

/**
 * Represents a DBField storing a JSON string
 */
class DBJson extends DBField
{

    public function __construct($name = null, $defaultVal = [])
    {
        $this->defaultVal = is_array($defaultVal) ? $defaultVal : [];

        parent::__construct($name);
    }

    public function requireField()
    {
        $charset = Config::inst()->get('SilverStripe\ORM\Connect\MySQLDatabase', 'charset');
        $collation = Config::inst()->get('SilverStripe\ORM\Connect\MySQLDatabase', 'collation');

        $parts = [
            'datatype' => 'mediumtext',
            'character set' => $charset,
            'collate' => $collation,
            'arrayValue' => $this->arrayValue
        ];

        $values = [
            'type' => 'text',
            'parts' => $parts
        ];

        DB::require_field($this->tableName, $this->name, $values);
    }

    public function nullValue()
    {
        return null;
    }

    public function setValue($value, $record = null, $markChanged = true)
    {
        if (!$value) {
            $value = null;
        }

        if (is_string($value)) {
            $value = json_decode($value, true);
        } elseif ($value instanceof JsonData) {
            $value = $value->jsonSerialize();
        }

        return parent::setValue($value, $record, $markChanged);
    }

    public function prepValueForDB($value)
    {
        if (is_array($value) || $value instanceof JsonData) {
            $value = json_encode($value, JSON_FORCE_OBJECT);
        }

        return $value;
    }

    public function scalarValueOnly()
    {
        return false;
    }
}
