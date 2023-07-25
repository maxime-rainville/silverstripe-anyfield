<?php declare(strict_types=1);

namespace SilverStripe\AnyField;

use JsonSerializable;

/**
 * An object that can be serialized and deserialized to JSON.
 */
interface JsonData extends JsonSerializable
{
    /**
     * @param array|JsonData $data
     * @return $this
     */
    public function setData($data): self;
}
