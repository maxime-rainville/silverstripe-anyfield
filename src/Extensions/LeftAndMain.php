<?php

namespace SilverStripe\AnyField\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\AnyField\Type\Registry;
use SilverStripe\View\Requirements;

/**
 * Register a new Form Schema in LeftAndMain.
 */
class LeftAndMain extends Extension
{
    public function init()
    {
        Requirements::add_i18n_javascript('maxime-rainville/anyfield:client/lang', false, true);
        Requirements::javascript('silverstripe/admin:client/dist/js/bundle.js');
        Requirements::javascript('silverstripe/asset-admin:client/dist/js/bundle.js');
        Requirements::javascript('maxime-rainville/anyfield:client/dist/js/bundle.js');
        Requirements::css('maxime-rainville/anyfield:client/dist/styles/bundle.css');
    }

    public function updateClientConfig(&$clientConfig)
    {
        $clientConfig['form']['AnyField'] = [
            'schemaUrl' => $this->getOwner()->Link('methodSchema/Modals/AnyFieldForm'),
        ];
    }
}
