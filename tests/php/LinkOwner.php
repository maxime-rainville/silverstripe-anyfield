<?php

namespace SilverStripe\AnyField\Tests;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;
use SilverStripe\AnyField\Models\Link;

class LinkOwner extends DataObject
{
    private static $table_name = 'AnyField_LinkOwner';
    private static $db = [
        'Title' => 'Varchar',
    ];
    private static $has_many = [
        'Links' => Link::class,
    ];
}
