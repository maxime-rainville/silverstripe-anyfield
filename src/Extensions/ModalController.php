<?php

namespace SilverStripe\AnyField\Extensions;

use InvalidArgumentException;
use SilverStripe\Admin\ModalController as OwnerController;
use SilverStripe\Control\HTTPResponse_Exception;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\Form;
use SilverStripe\AnyField\Form\FormFactory;
use SilverStripe\AnyField\Services\AnyService;

/**
 * Extensions to apply to ModalController so it knows how to handle the DynamicLink action.
 *
 * This action receive a DataObjectClassKey and some data as a JSON string and retrieve a Form Schema for a
 * specific Link Type.
 */
class ModalController extends Extension
{
    private static array $url_handlers = [
        'editorAnchorLink/$ItemID' => 'editorAnchorLink', // Matches LeftAndMain::methodSchema args
    ];

    private static array $allowed_actions = [
        'AnyFieldForm',
    ];

    /**
     * Builds and returns a form schema for any field
     *
     * @return Form
     */
    public function AnyFieldForm(): Form
    {
        /** @var OwnerController $owner */
        $owner = $this->getOwner();

        $factory = FormFactory::singleton();

        $data = $this->getData();

        return $factory->getForm(
            $owner->getController(),
            "{$owner->getName()}/AnyFieldForm",
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
        $dataObjectKey = $this->getOwner()->controller->getRequest()->getVar('key');

        if (!$dataObjectKey) {
            throw new HTTPResponse_Exception(sprintf('key for class "%s" is required', static::class), 400);
        }

        $type = AnyService::create()->generateFieldDefinition($dataObjectKey);

        if (!$type) {
            throw new HTTPResponse_Exception(sprintf('%s is not a valid ClassName', $type), 400);
        }

        $data = $this->getData();

        return [
            'Data' => $data,
            'DataObjectClass' => $type,
            'DataObjectClassKey' => $dataObjectKey,
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
                throw new HTTPResponse_Exception('Could not parse JSON data for form schema', 400);
            }
        }

        return $data;
    }
}
