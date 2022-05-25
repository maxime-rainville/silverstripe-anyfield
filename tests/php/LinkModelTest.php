<?php

namespace SilverStripe\LinkField\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\LinkField\Models\ExternalLink;

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
