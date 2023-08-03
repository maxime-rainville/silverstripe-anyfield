<?php

namespace SilverStripe\AnyField\Tests\GraphQL;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\AnyField\GraphQL\AnyFieldDescriptionResolver;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\LinkField\Models\ExternalLink;
use SilverStripe\LinkField\Models\EmailLink;

class ReadAnyFieldDescriptionTest extends FunctionalTest
{

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
                'description' => 'http://www.google.co.nz',
                '__typename' => 'AnyFieldDescription'
            ],
            [
                'id' => 'aebc8afd-7fbc-4503-bc8f-3fd459a3f2de',
                'title' => 'My new email address',
                'description' => 'maxime@example.com',
                '__typename' => 'AnyFieldDescription'
            ]
        ];


        $this->logInWithPermission('CMS_ACCESS_CMSMain');
        $graphql = $this->graphql(
            'ReadAnyFieldDescription',
            <<<GRAPHQL
            query ReadAnyFieldDescription(\$dataStr: String!) {
                readAnyFieldDescription(dataStr: \$dataStr) {
                    id
                    description
                    title
                    __typename
                }
            }
GRAPHQL,
            ['dataStr' => json_encode($links)]
        );

        $this->assertEquals(
            $expected,
            $graphql['data']['readAnyFieldDescription'],
            'List of links should have been resolved to the expected description'
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

        $expected = [
            [
                'id' => '1',
                'title' => 'My update link',
                'description' => 'http://www.google.co.nz',
                '__typename' => 'AnyFieldDescription'
            ]
        ];

        $this->logInWithPermission('CMS_ACCESS_CMSMain');
        $graphql = $this->graphql(
            'ReadAnyFieldDescription',
            <<<GRAPHQL
            query ReadAnyFieldDescription(\$dataStr: String!) {
                readAnyFieldDescription(dataStr: \$dataStr) {
                    id
                    description
                    title
                    __typename
                }
            }
GRAPHQL,
            ['dataStr' => json_encode($link)]
        );

        $this->assertEquals(
            $expected,
            $graphql['data']['readAnyFieldDescription']
        );
    }

    private function graphql(string $ops, string $query, array $vars = []): array
    {
        $response = $this->post('/admin/graphql', '', ['content-type' => 'application/json'], null, json_encode([
            'query' => $query,
            'operationName' => $ops,
            'variables' => $vars
        ]));
        $body = json_decode($response->getBody(), true);
        return $body;
    }
}
