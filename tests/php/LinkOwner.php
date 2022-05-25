<?php

namespace SilverStripe\LinkField\Tests;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;
use SilverStripe\LinkField\Models\Link;

class LinkOwner extends DataObject
{
    private static $table_name = 'LinkOwner';
    private static $db = [
        'Title' => 'Varchar',
    ];
    private static $has_many = [
        'Links' => Link::class,
    ];
}
