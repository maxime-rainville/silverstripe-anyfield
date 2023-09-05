<?php

namespace SilverStripe\AnyField\Tests\Extensions;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Dev\SapphireTest;

class LeftAndMainTest extends SapphireTest
{

    protected $use_database = false;

    public function testClientConfig(): void
    {
        $config = LeftAndMain::singleton()->getClientConfig();
        $this->assertEquals(
            'admin/methodSchema/Modals/AnyFieldForm',
            $config['form']['AnyField']['schemaUrl'],
            'LeftAndMainClient config include link to retrieve anyfield from schemas'
        );
    }
}
