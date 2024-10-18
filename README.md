# Silverstripe CMS AnyField module

This module provides two simple form fields to manage has-one and has-many relations for a parent record.

`AnyField` and `ManyAnyField` are best suited to managing simple DataObjects that are tightly coupled to their owner. This module will not work well for DataObjects with complex relations.

[`silverstripe/linkfield`](https://github.com/silverstripe/silverstripe-linkfield) is a great complement to the `AnyField`.

## Installation

```sh
composer require maxime-rainville/anyfield
```

This module require Silverstripe CMS 5 or greater.

## Showcase
[Quick demo](https://github.com/maxime-rainville/silverstripe-anyfield/assets/1168676/659933d6-15cd-45df-a454-b08c4d957e9f)

- Manage `has_one` or `has_many` relations with ease
- Manage multiple Dataobject classes from a single field
- Allow your content authors to edit your child DataObjects from a modal within their parent page
- Works in regular Entwine forms and Elemental blocks.

## Sample usage

```php
<?php
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\AnyField\Form\AnyField;
use SilverStripe\AnyField\Form\ManyAnyField;

class Page extends SiteTree
{
    private static array $has_one = [
        'SingleLink' => Link::class,
    ];

    private static array $has_many = [
        'ManyLinks' => Link::class,
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab(
            'Root.Main',
            [
                AnyField::create('SingleLink'),
                ManyAnyField::create('ManyLinks'),
            ]
        )

        return $fields;
    }
}
```

## Customising how DataObject look in the field

Any DataObject can be managed by the AnyField and ManyAnyField without any special tweaks. However, you can get a bit more value with some simple tweaks. Those tweaks can be applied with DataExtension.

### Customising the Title

The AnyFields displays the selected DataObject title. By defining a `getTitle` method for DataObject class, you can customise the title displayed in the field.

### Showing a summary

The AnyFields displays the type of the selected DataObject below its title. You can also display a summary by implementing a `getSummary` method on your DataObject class. This can be done with a DataExtension as well.

### Showing an icon

You can customise the icon to display for each class of DataObject by defining a `private static $icon` config value for your DataObject.

[Silverstripe CMS comes with some predefined icons](https://silverstripe.github.io/silverstripe-pattern-lib/?path=/story/admin-icons--icon-reference) you can use. Alternatively, the `icon` value will be add as a CSS class to the relevant button which you can then target with your own custom icon.

```yml
# This will add a `font-icon-link` class to the AnyField when managing a Book class.
App\Models\Book:
  icon: book
```

## Sorting the ManyManyField

`ManyManyField` can be configured to sort its results. Called `setSort` to define what field should be used to sort the results

```php
ManyAnyField::create('Pets')->setSort('Sort')
```

A convenience extension can be applied to your DataObject classes to add the required field to make them sortable. The extension will automatically add a `Sort` integer field, define the default sort order and hide the `Sort` field from the CMS form.
```yml
App\Models\FooBar:
  extensions:
  - SilverStripe\AnyField\Extensions\Sortable
```


## Advanced use case

### Auto-publishing and cascade deleting

The AnyFields doesn't allow you to publish or delete child DataObjects independently from their owner. To avoid orphan objects or unpublished DataObject, you should explicitly define [ownership](https://docs.silverstripe.org/en/5/developer_guides/model/versioning#ownership) and [cascade rules](https://docs.silverstripe.org/en/5/developer_guides/model/relations/#cascading-deletions) on the owner DataObject class.

```php
<?php
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\AnyField\Form\AnyField;
use SilverStripe\AnyField\Form\ManyAnyField;

class Page extends SiteTree
{
    private static array $has_one = [
        'SingleLink' => Link::class,
    ];

    private static array $has_many = [
        'ManyLinks' => Link::class,
    ];

    /** Publishing the page will automatically publish those relations */
    private static $owns = [
        'SingleLink',
        'ManyLinks'
    ];

    /** The relations will be deleted when the page is deleted */
    private static $cascade_deletes = [
        'SingleLink',
        'ManyLinks'
    ];

    /** The relations will be duplicated when the page is duplicated */
    private static $cascade_duplicates = [
        'SingleLink',
        'ManyLinks'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab(
            'Root.Main',
            [
                AnyField::create('SingleLink'),
                ManyAnyField::create('ManyLinks'),
            ]
        )

        return $fields;
    }
}
```
### Controlling what DataObject class can be created via the AnyFields

AnyField and ManyAnyField try to automatically detect what DataObject classes they are meant to manage by looking up the matching relations on their parent object.

If you decide to name the AnyFields something other than its intended relation, you'll have to explicitly tell it what DataObject class to use.

```php
AnyField::create('MyItem', 'My Item', $this->Item)->setBaseClass(Link::class);
```

By the default, the AnyField automatically discovers any sub classes of the base class and allows the end user to create those sub classes. You can turn off this behaviour by calling `setRecursivelyAddChildClass`.

```php
AnyField::create('MyItem')->setRecursivelyAddChildClass(false);
```

You can also exclude individual classes as well.

```php
// The filed will allow you to create child classes of Link, but not a plain Link
AnyField::create('MyLink')->setBaseClass(Link::class)->addExcludedClass(Link::class);
```
