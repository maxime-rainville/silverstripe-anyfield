<?php
namespace SilverStripe\AnyField\Tests\Services;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\AnyField\Services\AnyService;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\LinkField\Models\FileLink;
use SilverStripe\Core\Injector\InjectorNotFoundException;
use SilverStripe\LinkField\Models\SiteTreeLink;
use SilverStripe\LinkField\Models\EmailLink;
use SilverStripe\LinkField\Models\ExternalLink;
use SilverStripe\LinkField\Models\PhoneLink;

class AnyServiceTest extends SapphireTest
{

    protected static $fixture_file = '../LinkModelTest.yml';

    public function testGenerateFieldDefinition(): void
    {
        $service = new AnyService();
        $this->assertEquals(
            [
                'key' => FileLink::class,
                'title' => 'File Link',
                'icon' => 'menu-files',
                'modalHandler' => 'InsertMediaModal',
            ],
            $service->generateFieldDefinition(FileLink::class),
        );
    }

    public function testGenerateFieldDefinitionBadClass(): void
    {
        $service = new AnyService();
        $this->expectException(InjectorNotFoundException::class);
        $service->generateFieldDefinition(AnyService::class);
    }

    public function testGenerateDescription(): void
    {
        $service = new AnyService();
        $this->assertEquals(
            [
                'title' => 'My link title',
                'description' => 'about-us',
            ],
            $service->generateDescription(
                SiteTreeLink::class,
                [
                    'PageID' => $this->idFromFixture(\Page::class, 'about-us'),
                    'Title' => 'My link title',
                ]
            ),
        );
    }

    public function testGenerateDescriptionBadClass(): void
    {
        $service = new AnyService();
        $this->expectException(InjectorNotFoundException::class);
        $service->generateDescription(
            \My\NonSense\Class::class,
            [
                'PageID' => $this->idFromFixture(\Page::class, 'about-us'),
                'Title' => 'My link title',
            ]
        );
    }

    public function testMap(): void
    {
        $service = new AnyService();
        $link = $this->objFromFixture(EmailLink::class, 'link-3');
        $this->assertPartialMatch(
            [
                'ID' => $link->ID,
                'Title' => 'Link3',
                'Email' => 'hello@silverstripe.org',
                'dataObjectClassKey' => EmailLink::class
            ],
            $service->map($link),
            'Map should return a map of the object fields'
        );
    }

    public function testMapList(): void
    {
        $service = new AnyService();
        $links = Link::get();
        $results = $service->mapList($links);
        $expected = [];
        foreach ($links as $link) {
            $expected[] = $service->map($link);
        }

        $this->assertEquals(
            $expected,
            $results,
            'MapList should return a the list of all the maps of the object in the list'
        );
    }

    public function testJsonSerialize(): void
    {
        $service = new AnyService();
        $link = $this->objFromFixture(EmailLink::class, 'link-3');
        $this->assertPartialMatch(
            [
                'ID' => $link->ID,
                'Title' => 'Link3',
                'Email' => 'hello@silverstripe.org',
                'dataObjectClassKey' => EmailLink::class
            ],
            json_decode($service->jsonSerialize($link), true),
            'JsonSerialize should return a json string of the dataobject'
        );
    }

    public function testSetDataWithPlainUpdate()
    {
        $service = new AnyService();
        $link = $this->objFromFixture(EmailLink::class, 'link-3');

        $updatedLink = $service->setData(
            $link,
            [
                'ID' => $link->ID,
                'Title' => 'Updated title',
                'Email' => 'updated@example.com',
                'dataObjectClassKey' => EmailLink::class
            ]
        );

        $this->assertEquals(
            'Updated title',
            $updatedLink->Title,
            'Data should have been updated'
        );
        $this->assertEquals(
            'updated@example.com',
            $updatedLink->Email,
            'Data should have been updated'
        );
        $this->assertEquals(
            $link->ID,
            $updatedLink->ID,
            'ID should not have changed'
        );
        $this->assertEquals(
            $link->ClassName,
            $updatedLink->ClassName,
            'ClassName should not have changed'
        );
    }


    public function testSetDataWithInvalidDataObjectClass()
    {
        $this->expectException(InjectorNotFoundException::class);
        $service = new AnyService();
        $link = $this->objFromFixture(EmailLink::class, 'link-3');
        $updatedLink = $service->setData(
            $link,
            [
                'ID' => $link->ID,
                'Title' => 'Updated title',
                'Email' => 'updated@example.com',
                'dataObjectClassKey' => AnyService::class
            ]
        );
    }

    public function testSetDataWithUpdateClassName()
    {
        $service = new AnyService();
        $link = $this->objFromFixture(ExternalLink::class, 'link-2');

        $updatedLink = $service->setData(
            $link,
            [
                'ID' => $link->ID,
                'Title' => 'Updated title',
                'Email' => 'updated@example.com',
                'dataObjectClassKey' => EmailLink::class
            ]
        );

        $this->assertEquals(
            'Updated title',
            $updatedLink->Title,
            'Data should have been updated'
        );
        $this->assertEquals(
            'updated@example.com',
            $updatedLink->Email,
            'Data should have been updated'
        );
        $this->assertEquals(
            $link->ID,
            $updatedLink->ID,
            'ID should not have changed'
        );
        $this->assertEquals(
            EmailLink::class,
            $updatedLink->ClassName,
            'ClassName should have changed'
        );
    }

    public function testSetDataWithBrandNewObject()
    {
        $service = new AnyService();
        $link = new EmailLink();

        $updatedLink = $service->setData(
            $link,
            [
                'ID' => $link->ID,
                'Title' => 'Updated title',
                'Email' => 'updated@example.com',
                'dataObjectClassKey' => EmailLink::class
            ]
        );

        $this->assertEquals(
            'Updated title',
            $updatedLink->Title,
            'Data should have been updated'
        );
        $this->assertEquals(
            'updated@example.com',
            $updatedLink->Email,
            'Data should have been updated'
        );
        $this->assertEquals(
            0,
            $updatedLink->ID,
            'ID should not have been saved to DB yet'
        );
        $this->assertEquals(
            EmailLink::class,
            $updatedLink->ClassName,
            'ClassName should match what is is our array'
        );
    }

    /**
     * @dataProvider allowedDataObjectClassesProvider
     */
    public function testAllowedDataObjectClasses(
        string $baseClass,
        bool $recursivelyAddChildClass,
        array $excludedClasses,
        array $expected,
    ) {
        $service = new AnyService();
        $this->assertEquals(
            $expected,
            $service->getAllowedDataObjectClasses(
                $baseClass,
                $recursivelyAddChildClass,
                $excludedClasses
            )
        );
    }

    public function allowedDataObjectClassesProvider()
    {
        return [
            'Recursive with no excluded classes' => [
                Link::class,
                true,
                [],
                [
                    FileLink::class => [
                        'key' => FileLink::class,
                        'title' => 'File Link',
                        'icon' => 'menu-files',
                        'modalHandler' => 'InsertMediaModal',
                    ],
                    SiteTreeLink::class => [
                        'key' => SiteTreeLink::class,
                        'title' => 'Site Tree Link',
                        'icon' => 'page',
                        'modalHandler' => null,
                    ],
                    EmailLink::class => [
                        'key' => EmailLink::class,
                        'title' => 'Email Link',
                        'icon' => 'p-mail',
                        'modalHandler' => null,
                    ],
                    ExternalLink::class => [
                        'key' => ExternalLink::class,
                        'title' => 'External Link',
                        'icon' => 'external-link',
                        'modalHandler' => '',
                    ],
                    Link::class => [
                        'key' => Link::class,
                        'title' => 'Link',
                        'icon' => null,
                        'modalHandler' => null,
                    ],
                    PhoneLink::class => [
                        'key' => PhoneLink::class,
                        'title' => 'Phone Link',
                        'icon' => 'link',
                        'modalHandler' => null,
                    ]
                ]
            ],
            'Recursive with excluded classes' => [
                Link::class,
                true,
                [
                    FileLink::class,
                    SiteTreeLink::class,
                ],
                [
                    EmailLink::class => [
                        'key' => EmailLink::class,
                        'title' => 'Email Link',
                        'icon' => 'p-mail',
                        'modalHandler' => null,
                    ],
                    ExternalLink::class => [
                        'key' => ExternalLink::class,
                        'title' => 'External Link',
                        'icon' => 'external-link',
                        'modalHandler' => '',
                    ],
                    Link::class => [
                        'key' => Link::class,
                        'title' => 'Link',
                        'icon' => null,
                        'modalHandler' => null,
                    ],
                    PhoneLink::class => [
                        'key' => PhoneLink::class,
                        'title' => 'Phone Link',
                        'icon' => 'link',
                        'modalHandler' => null,
                    ]
                ]
            ],
            'Non recursive' => [
                Link::class,
                false,
                [],
                [
                    Link::class => [
                        'key' => Link::class,
                        'title' => 'Link',
                        'icon' => null,
                        'modalHandler' => null,
                    ]
                ]
            ],
            'No child classes' => [
                EmailLink::class,
                true,
                [],
                [
                    EmailLink::class => [
                        'key' => EmailLink::class,
                        'title' => 'Email Link',
                        'icon' => 'p-mail',
                        'modalHandler' => null,
                    ],
                ]
            ]
        ];
    }

    private function assertPartialMatch($expected, $actual, $message = null)
    {
        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $actual, $message);
            $this->assertEquals($value, $actual[$key], $message);
        }
    }
}
