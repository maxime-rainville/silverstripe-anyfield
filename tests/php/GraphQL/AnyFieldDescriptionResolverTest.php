<?php

namespace SilverStripe\AnyField\Tests\GraphQL;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\AnyField\GraphQL\AnyFieldDescriptionResolver;
use SilverStripe\LinkField\Models\ExternalLink;
use SilverStripe\LinkField\Models\EmailLink;

class AnyFieldDescriptionResolverTest extends SapphireTest
{

    public function testBadJsonString()
    {
        $this->expectException(\InvalidArgumentException::class);
        AnyFieldDescriptionResolver::resolve([], ['dataStr' => 'non-sense'], [], null);
    }

    public function testListOfLinks()
    {
        $links = [
            [
                'ID' => '1',
                'Title' => 'My update link',
                'ExternalUrl' => 'http://www.google.co.nz',
                'dataObjectClassKey' => ExternalLink::class,
            ],
            [
                'Title' => 'My new email address',
                'OpenInNew' => 1,
                'Email' => 'maxime@example.com',
                'ID' => 'aebc8afd-7fbc-4503-bc8f-3fd459a3f2de',
                'dataObjectClassKey' => EmailLink::class
            ]
        ];
        $expected = [
            [
                'id' => '1',
                'title' => 'My update link',
                'description' => 'http://www.google.co.nz'
            ],
            [
                'id' => 'aebc8afd-7fbc-4503-bc8f-3fd459a3f2de',
                'title' => 'My new email address',
                'description' => 'maxime@example.com'
            ]
        ];
        $this->assertEquals(
            $expected,
            AnyFieldDescriptionResolver::resolve([], ['dataStr' => json_encode($links)], [], null),
            'Link list data should have been resolved to the expected description'
        );
    }

    public function testSingleLink()
    {
        $link = [
            'ID' => '1',
            'Title' => 'My update link',
            'ExternalUrl' => 'http://www.google.co.nz',
            'dataObjectClassKey' => ExternalLink::class,
        ];

        $results = AnyFieldDescriptionResolver::resolve([], ['dataStr' => json_encode($link)], [], null);
        $expected = [
            [
                'id' => '1',
                'title' => 'My update link',
                'description' => 'http://www.google.co.nz'
            ]
        ];
        $this->assertEquals(
            $expected,
            AnyFieldDescriptionResolver::resolve([], ['dataStr' => json_encode($link)], [], null),
            'Single Link data should have been resolved to the expected description'
        );
    }
}
