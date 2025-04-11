<?php

namespace SilverStripe\AnyField\Extensions;

use SilverStripe\AnyField\Services\AnyService;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HiddenField;
use SilverStripe\ORM\DataObject;

/**
 * Utility extension that can be added to DataObject managed through a ManyAnyField to
 * make it easy to sort
 *
 * @method DataObject getOwner()
 */
class SortableModelExtension extends Extension
{

    private static array $db = [
        'Sort' => 'Int',
    ];

    private static array $default_sort = [
        'Sort' => 'ASC',
    ];

    /**
     * Add a sort field to allow the Sort data to be correctly saved into the model
     * @see AnyService::setData()
     *
     * @param FieldList $fields
     * @return void
     */
    public function updateCMSFields(FieldList $fields): void
    {
        $sortField = HiddenField::create('Sort');
        $fields->add($sortField);
    }
}
