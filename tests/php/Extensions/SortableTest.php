<?php

namespace SilverStripe\AnyField\Tests\Extensions;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\AnyField\Extensions\Sortable;

class SortableTest extends SapphireTest
{
    protected static $required_extensions = [
        Link::class => [
            Sortable::class,
        ],
    ];

    public function testGetCMSFields()
    {
        $link = Link::create();
        $fields = $link->getCMSFields();

        $this->assertNull($fields->fieldByName('Root.Main.Sort'), 'Sort field has been removed from FieldList');
        $this->assertNotNull($fields->fieldByName('Root.Main.Title'));
    }
}
