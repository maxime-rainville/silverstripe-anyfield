<?php

namespace SilverStripe\AnyField\Tests\Form\ManyAnyFieldTest;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\Connect\MySQLSchemaManager;
use SilverStripe\ORM\DataObject;
use SilverStripe\LinkField\Models\Link;

class ManyLink extends DataObject implements TestOnly
{
    private static $table_name = 'DatabaseTest_ManyLink';

    private static $has_many = [
        'Links' => Link::class,
    ];
}
