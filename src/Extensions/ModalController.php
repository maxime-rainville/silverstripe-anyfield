<?php

namespace SilverStripe\AnyField\Extensions;

use InvalidArgumentException;
use SilverStripe\Admin\ModalController as OwnerController;
use SilverStripe\Control\HTTPResponse_Exception;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\Form;
use SilverStripe\AnyField\Form\FormFactory;
use SilverStripe\AnyField\Type\Registry;

/**
 * Extensions to apply to ModalController so it knows how to handle the DynamicLink action.
 *
 * This action receive a link type key and some link data as a JSON string and retrieve a Form Schema for a
 * specific Link Type.
 */
class ModalController extends Extension
{
    private static array $url_handlers = [
        'editorAnchorLink/$ItemID' => 'editorAnchorLink', // Matches LeftAndMain::methodSchema args
    ];

    private static array $allowed_actions = [
        'DynamicLink',
    ];

    /**
     * Builds and returns the external link form
     *
     * @return Form
     */
    public function DynamicLink()
    {
        // Show link text field if requested
        $linkDataJsonStr = $this->getOwner()->controller->getRequest()->getVar('data');

        /** @var OwnerController $owner */
        $owner = $this->getOwner();

        $factory = FormFactory::singleton();

        $data = $this->getData();

        return $factory->getForm(
            $owner->getController(),
            "{$owner->getName()}/DynamicLink",
            $this->getContext()
        )->loadDataFrom($data);
    }

    /**
     * Build the context to pass to the Form Link Factory
     * @return array
     * @throws HTTPResponse_Exception
     */
    private function getContext(): array
    {
        $linkTypeKey = $this->getOwner()->controller->getRequest()->getVar('key');

        if (!$linkTypeKey) {
            throw new HTTPResponse_Exception(sprintf('key for class "%s" is required', static::class), 400);
        }

        $type = Registry::singleton()->byKey($linkTypeKey);

        if (!$type) {
            throw new HTTPResponse_Exception(sprintf('%s is not a valid link type', $type), 400);
        }

        return [
            'LinkData' => $this->getData(),
            'LinkType' => $type,
            'LinkTypeKey' => $linkTypeKey,
            'RequireLinkText' => false
        ];
    }

    /**
     * Extract the Link Data out of the Request.
     * @return array
     */
    private function getData(): array
    {
        $data = [];
        $dataString = $this->getOwner()->controller->getRequest()->getVar('data');

        if ($dataString) {
            $parsedData = json_decode($dataString, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $data = $parsedData;
            } else {
                throw new InvalidArgumentException(json_last_error_msg());
            }
        }

        return $data;
    }
}
