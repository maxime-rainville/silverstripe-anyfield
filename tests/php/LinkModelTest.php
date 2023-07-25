<?php

namespace SilverStripe\AnyField\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\AnyField\Models\Link;
use SilverStripe\AnyField\Models\ExternalLink;

class LinkModelTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = 'LinkModelTest.yml';

    public function testLinkModel(): void
    {
        $model = $this->objFromFixture(ExternalLink::class, 'link-1');

        $this->assertEquals('FormBuilderModal', $model->LinkTypeHandlerName());
    }
}
