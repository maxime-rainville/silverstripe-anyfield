<?php

namespace SilverStripe\AnyField\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\AnyField\Type\Registry;

/**
 * Register a new Form Schema in LeftAndMain.
 */
class LeftAndMain extends Extension
{
    public function init()
    {
        // Get the Link Registry to load all the JS requirements for managing Links.
        Registry::singleton()->init();
    }

    public function updateClientConfig(&$clientConfig)
    {
        $clientConfig['form']['DynamicLink'] = [
            'schemaUrl' => $this->getOwner()->Link('methodSchema/Modals/DynamicLink'),
        ];
    }
}
