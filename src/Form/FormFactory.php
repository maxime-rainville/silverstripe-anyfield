<?php

namespace SilverStripe\AnyField\Form;

use LogicException;
use SilverStripe\Admin\Forms\LinkFormFactory;
use SilverStripe\Forms\HiddenField;
use SilverStripe\AnyField\Type\Type;
use SilverStripe\ORM\DataObject;

/**
 * Create Form schema for the LinkField based on a key provided by the request.
 */
class FormFactory extends LinkFormFactory
{
    protected function getFormFields($controller, $name, $context)
    {
        /** @var Type $type */
        $dataObjectClass = DataObject::singleton($context['DataObjectClassKey']);

        if (!$dataObjectClass instanceof DataObject) {
            var_dump($dataObjectClass);
            throw new LogicException(sprintf('%s: DataObjectClass must be provided and must be an instance of DataObject', static::class));
        }

        $fields = $dataObjectClass->scaffoldLinkFields([]);
        $fields->push(HiddenField::create('dataObjectClassKey')->setValue($context['DataObjectClassKey']));
        $this->extend('updateFormFields', $fields, $controller, $name, $context);

        return $fields;
    }

    protected function getValidator($controller, $name, $context)
    {
        return null;
    }
}
