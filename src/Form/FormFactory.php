<?php

namespace SilverStripe\AnyField\Form;

use LogicException;
use SilverStripe\Admin\Forms\LinkFormFactory;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HiddenField;
use SilverStripe\ORM\DataObject;

/**
 * Create Form schema for the LinkField based on a key provided by the request.
 */
class FormFactory extends LinkFormFactory
{
    protected function getFormFields($controller, $name, $context)
    {
        $dataObjectClass = DataObject::singleton($context['DataObjectClassKey']);

        if (!$dataObjectClass instanceof DataObject) {
            throw new LogicException(sprintf('%s: DataObjectClass must be provided and must be an instance of DataObject', static::class));
        }

        $fields = $dataObjectClass->getCMSFields();
        $fields->push(HiddenField::create('ID'));
        $fields->push(HiddenField::create('dataObjectClassKey')->setValue($context['DataObjectClassKey']));
        $this->extend('updateFormFields', $fields, $controller, $name, $context);

        return $fields;
    }

    protected function getFormActions($controller, $name, $context)
    {
        $actions = parent::getFormActions($controller, $name, $context);

        /** @var FormAction $insertAction */
        $insertAction = $actions->fieldByName('action_insert');

        // Update action button to be more descriptive
        $insertAction?->setTitle(_t('SilverStripe\\Forms\\GridField\\GridFieldDetailForm.Save', 'Save'));

        return $actions;
    }

    protected function getValidator($controller, $name, $context)
    {
        return null;
    }
}
