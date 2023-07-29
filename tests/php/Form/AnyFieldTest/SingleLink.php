<?php

namespace SilverStripe\AnyField\Tests\Form\AnyFieldTest;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\Connect\MySQLSchemaManager;
use SilverStripe\ORM\DataObject;
use SilverStripe\LinkField\Models\Link;

class SingleLink extends DataObject implements TestOnly
{
    private static $table_name = 'DatabaseTest_SingleLink';

    private static $has_one = [
        'Link' => Link::class,
    ];
}
