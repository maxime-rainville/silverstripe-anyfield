<?php

namespace SilverStripe\AnyField\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HiddenField;
use SilverStripe\ORM\DataExtension;

/**
 * Utility extension that can be added to DataObject managed through a ManyAnyField to
 * make it easy to sort
 */
class Sortable extends DataExtension
{

    private static array $db = [
        'Sort' => 'Int',
    ];

    private static array $default_sort = [
        'Sort' => 'ASC',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->add(HiddenField::create('Sort'));
    }
}
