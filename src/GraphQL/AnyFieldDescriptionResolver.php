<?php

namespace SilverStripe\AnyField\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use InvalidArgumentException;
use SilverStripe\AnyField\Services\AnyService;
use SilverStripe\GraphQL\Schema\DataObject\Resolver;
use SilverStripe\AnyField\Type\Registry;

class AnyFieldDescriptionResolver extends Resolver
{
    public static function resolve($obj, $args = [], $context = [], ?ResolveInfo $info = null)
    {
        $data = json_decode($args['dataStr'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('data must be a valid JSON string');
        }

        if (self::hasStringKeys($data)) {
            $data = [$data];
        }

        return array_map([self::class, 'resolveSingleDescription'], $data);
    }

    /**
     * Return a description for a specific link
     */
    private static function resolveSingleDescription($data): array
    {
        $id = isset($data['ID']) ? $data['ID'] : 0;
        $description = ['title' => '', 'description' => ''];

        // If we don't have a valid typeKey, we'll return a blank description
        if (!empty($data['dataObjectClassKey'])) {
            $description = AnyService::create()->generateDescription(
                $data['dataObjectClassKey'],
                $data
            );
        }

        return array_merge(['id' => $id], $description);
    }

    /**
     * Check if our array is sequential or associative. We are assuming that an array with string key is associative.
     */
    private static function hasStringKeys(array $array): bool
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }
}
