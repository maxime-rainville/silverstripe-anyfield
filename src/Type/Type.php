<?php

namespace SilverStripe\LinkField\Type;

use SilverStripe\Forms\FieldList;
use SilverStripe\LinkField\JsonData;

/**
 * Define a link type that can be edited in Link Field
 */
interface Type
{
    /**
     * Call once on the main request. Can be used to require front end assets.
     */
    public function defineLinkTypeRequirements();

    /**
     * Each Type of link must specify a frontend handler that will determine what happens when it gets selected.
     */
    public function LinkTypeHandlerName(): string;

    /**
     * Font icon to attach to this link type
     */
    public function LinkTypeIcon(): string;

    /**
     * What should be the link description be given this data.
     */
    public function generateLinkDescription(array $data): array;

    /**
     * Human readable title of this link type
     */
    public function LinkTypeTitle(): string;

    /**
     * Build a list of fields suitable to edit this link type
     * @param array $data
     * @return FieldList
     */
    public function scaffoldLinkFields(array $data): FieldList;

    /**
     * Create a new instance of this Link from the provided Data
     * @param array $data
     * @return JsonData
     */
    public function loadLinkData(array $data): JsonData;
}
