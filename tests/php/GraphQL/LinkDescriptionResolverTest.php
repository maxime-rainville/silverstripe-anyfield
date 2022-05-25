<?php

namespace SilverStripe\Link\Tests\Form;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\LinkField\GraphQL\LinkDescriptionResolver;

class LinkDescriptionResolverTest extends SapphireTest
{

    public function testBadJsonString()
    {
        $this->expectException(\InvalidArgumentException::class);
        LinkDescriptionResolver::resolve([], ['dataStr' => 'non-sense'], [], null);
    }

    public function testListOfLinks()
    {
        $links = [
            [
                'ID' => '1',
                'Title' => 'My update link',
                'ExternalUrl' => 'http://www.google.co.nz',
                'typeKey' => 'external',
            ],
            [
                'Title' => 'My new email address',
                'OpenInNew' => 1,
                'Email' => 'maxime@example.com',
                'ID' => 'aebc8afd-7fbc-4503-bc8f-3fd459a3f2de',
                'typeKey' => 'email',
                'isNew' => true
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
            LinkDescriptionResolver::resolve([], ['dataStr' => json_encode($links)], [], null),
            'Link list data should have been resolved to the expected description'
        );
    }

    public function testSingleLink()
    {
        $link = [
            'ID' => '1',
            'Title' => 'My update link',
            'ExternalUrl' => 'http://www.google.co.nz',
            'typeKey' => 'external',
        ];

        $results = LinkDescriptionResolver::resolve([], ['dataStr' => json_encode($link)], [], null);
        $expected = [
            [
                'id' => '1',
                'title' => 'My update link',
                'description' => 'http://www.google.co.nz'
            ]
        ];
        $this->assertEquals(
            $expected,
            LinkDescriptionResolver::resolve([], ['dataStr' => json_encode($link)], [], null),
            'Single Link data should have been resolved to the expected description'
        );
    }
}
