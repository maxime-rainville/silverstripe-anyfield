<?php

namespace SilverStripe\AnyField\Extensions;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;

/**
 * Register a new Form Schema for AnyField
 *
 * @method LeftAndMain getOwner()
 */
class SchemaControllerExtension extends Extension
{
    public function onInit(): void
    {
        Requirements::add_i18n_javascript('maxime-rainville/anyfield:client/lang', false, true);
        Requirements::javascript('maxime-rainville/anyfield:client/dist/js/bundle.js', ['defer' => true]);
        Requirements::css('maxime-rainville/anyfield:client/dist/styles/bundle.css');
    }

    /**
     * Extension point in @see LeftAndMain::getClientConfig()
     *
     * @param array $clientConfig
     * @return void
     */
    public function updateClientConfig(array &$clientConfig): void
    {
        $clientConfig['form']['AnyField'] = [
            'schemaUrl' => $this->getOwner()->Link('methodSchema/Modals/AnyFieldForm'),
        ];
    }
}
