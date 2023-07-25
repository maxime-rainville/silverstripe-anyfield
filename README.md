# Silverstripe link module

This module provides a Link model and CMS interface for managing different types of links. Including:

* Emails
* External links
* Links to pages within the CMS
* Links to assets within the CMS
* Phone numbers

## Installation

Installation via composer.

### Silverstripe 5

```sh
composer require silverstripe/linkfield
```

### GraphQL v4 - Silverstripe 4

`composer require silverstripe/linkfield:^2`

### GraphQL v3 - Silverstripe 4

```sh
composer require silverstripe/linkfield:^1
```

## Sample usage

```php
<?php
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\AnyField\ORM\DBLink;
use SilverStripe\AnyField\Models\Link;
use SilverStripe\AnyField\Form\LinkField;

class Page extends SiteTree
{
    private static array $db = [
        'DbLink' => DBLink::class
    ];

    private static array $has_one = [
        'HasOneLink' => Link::class,
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab(
            'Root.Main',
            [
                LinkField::create('HasOneLink'),
                LinkField::create('DbLink'),
            ]
        )

        return $fields;
    }
}
```

## Migrating from Version `1.0.0` or `dev-master`

Please be aware that in early versions of this module (and in untagged `dev-master`) there were no table names defined
for our `Link` classes. These have now all been defined, which may mean that you need to rename your old tables, or
migrate the data across.

EG: `SilverStripe_LinkField_Models_Link` needs to be migrated to `LinkField_Link`.

## Migrating from Shae Dawson's Linkable module

https://github.com/sheadawson/silverstripe-linkable

Shae Dawson's Linkable module was a much loved, and much used module. It is, unfortunately, no longer maintained. We
have provided some steps and tasks that we hope can be used to migrate your project from Linkable to LinkField.

* [Migraiton docs](docs/en/linkable-migration.md)
