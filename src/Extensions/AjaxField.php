<?php

namespace SilverStripe\AnyField\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FormField;
use League\Uri\Modifier;

/**
 * Tweak fields that need to be served through the DynamicLink form schema and need to be able to receive AJAX calls.
 *
 * For example the TreeDropdownField need to be able to receive AJAX request to fetch the list of available SiteTrees.
 *
 * This is a bit hackish. There's probably a less dumb way of doing this.
 */
class AjaxField extends Extension
{
    public function updateLink(&$link, $action)
    {
        /** @var FormField $owner */
        $owner = $this->getOwner();
        $formName = $owner->getForm()->getName();

        if ($formName !== 'Modals/AnyFieldForm') {
            return;
        }

        $request = $owner->getForm()->getController()->getRequest();
        $key = $request->getVar('key');

        $link = Modifier::from($link)->mergeQuery("key={$key}")->getUri()->__toString();
    }
}
