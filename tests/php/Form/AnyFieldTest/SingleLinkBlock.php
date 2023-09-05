<?php

namespace SilverStripe\AnyField\Tests\Form\AnyFieldTest;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\Connect\MySQLSchemaManager;
use SilverStripe\ORM\DataObject;
use SilverStripe\LinkField\Models\Link;
use DNADesign\Elemental\Models\BaseElement;

class SingleLinkBlock extends BaseElement implements TestOnly
{
    private static $table_name = 'DatabaseTest_SingleLinkBlock';

    private static $has_one = [
        'Link' => Link::class,
    ];
}
