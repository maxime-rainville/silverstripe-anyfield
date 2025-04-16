<?php

namespace SilverStripe\AnyField\Extensions;

use League\Uri\Modifier;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FormField;

/**
 * Tweak fields that need to be served through the DynamicLink form schema and need to be able to receive AJAX calls.
 * For example the TreeDropdownField need to be able to receive AJAX request to fetch the list of available SiteTrees.
 * TODO This is a bit hackish. There's probably a less dumb way of doing this.
 *
 * @method FormField getOwner()
 */
class AjaxFieldLinkExtension extends Extension
{
    /**
     * Extension point in @see FormField::Link()
     *
     * @param string $link
     * @param string|null $action
     * @return void
     */
    public function updateLink(string &$link, ?string $action): void
    {
        $owner = $this->getOwner();
        $formName = $owner->getForm()->getName();

        if ($formName !== 'Modals/AnyFieldForm') {
            return;
        }

        $request = $owner
            ->getForm()
            ->getController()
            ->getRequest();
        $key = $request->getVar('key');

        $link = (string) Modifier::from($link)
            ->mergeQuery(sprintf('key=%s', $key))
            ->getUri();
    }
}
