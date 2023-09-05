<?php

namespace SilverStripe\AnyField\Tests\Form\AnyFieldTest;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\Connect\MySQLSchemaManager;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\ORM\DataExtension;

class ForcePermissionExtension extends DataExtension implements TestOnly
{

    public static array $forcePermissionCheck = [

    ];

    public function forcePermission($perm, $value)
    {
        self::$forcePermissionCheck[$perm] = $value;
    }

    private function forceCanCheck($perm)
    {
        if (isset(self::$forcePermissionCheck[$perm])) {
            return self::$forcePermissionCheck[$perm];
        }
    }

    public function canCreate($member)
    {
        return $this->forceCanCheck('create');
    }

    public function canEdit($member)
    {
        return $this->forceCanCheck('update');
    }

    public function canDelete($member)
    {
        return $this->forceCanCheck('delete');
    }

    public function resetForcePermission()
    {
        self::$forcePermissionCheck = [];
    }
}
