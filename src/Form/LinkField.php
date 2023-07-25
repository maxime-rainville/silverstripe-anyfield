<?php declare(strict_types=1);

namespace SilverStripe\AnyField\Form;

/**
 * Allows CMS users to edit a Link object.
 */
class LinkField extends JsonField
{
    protected $schemaComponent = 'LinkField';

    public function setValue($value, $data = null)
    {
        return parent::setValue($value, $data);
    }
}
