<?php

namespace SilverStripe\LinkField\Tests\Models;

use ReflectionException;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\LinkField\Models\EmailLink;
use SilverStripe\LinkField\Models\ExternalLink;
use SilverStripe\LinkField\Models\FileLink;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\LinkField\Models\PhoneLink;
use SilverStripe\LinkField\Models\SiteTreeLink;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ValidationException;

class LinkTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = 'LinkTest.yml';

    public function testLinkModel(): void
    {
        $model = $this->objFromFixture(Link::class, 'link-1');

        $this->assertEquals('FormBuilderModal', $model->LinkTypeHandlerName());
    }

    /**
     * @throws ValidationException
     */
    public function testSiteTreeLinkTitleFallback(): void
    {
        /** @var SiteTreeLink $model */
        $model = $this->objFromFixture(SiteTreeLink::class, 'page-link-1');

        $this->assertEquals('PageLink1', $model->Title, 'We expect to get a default Link title');

        /** @var SiteTree $page */
        $page = $this->objFromFixture(SiteTree::class, 'page-1');

        $model->PageID = $page->ID;
        $model->Title = null;
        $model->write();

        // The actual Database Title field should still be null
        $this->assertNull($model->getField('Title'));
        // But when we fetch the field (ViewableData) it should return the value from getTitle()
        $this->assertEquals($page->Title, $model->Title, 'We expect to get the linked Page title');

        $customTitle = 'My custom title';
        $model->Title = $customTitle;
        $model->write();

        $this->assertEquals($customTitle, $model->FrontendTitle(), 'We expect to get the custom title not page title');
    }

    /**
     * @param string $class
     * @param bool $expected
     * @throws ReflectionException
     * @dataProvider linkTypeProvider
     */
    public function testLinkType(string $class, bool $expected): void
    {
        /** @var Link $model */
        $model = DataObject::singleton($class);
        $fields = $model->getCMSFields();
        $linkTypeField = $fields->fieldByName('Root.Main.LinkType');
        $expected
            ? $this->assertNotNull($linkTypeField, 'We expect to a find link type field')
            : $this->assertNull($linkTypeField, 'We do not expect to a find link type field');
    }

    public function linkTypeProvider(): array
    {
        return [
            [EmailLink::class, false],
            [ExternalLink::class, false],
            [FileLink::class, false],
            [PhoneLink::class, false],
            [SiteTreeLink::class, false],
            [Link::class, true],
        ];
    }
}
