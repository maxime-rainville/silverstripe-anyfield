<?php declare(strict_types=1);

namespace SilverStripe\LinkField\Models;

/**
 * A link to an external URL.
 *
 * @property string $ExternalUrl
 */
class ExternalLink extends Link
{
    private static string $table_name = 'LinkField_ExternalLink';

    private static array $db = [
        'ExternalUrl' => 'Varchar',
    ];

    private static $icon = 'external-link';

    public function generateLinkDescription(array $data): array
    {
        $description = isset($data['ExternalUrl']) ? $data['ExternalUrl'] : '';
        $title = empty($data['Title']) ? $description : $data['Title'];
        return [
            'title' => $title,
            'description' => $description
        ];
    }

    public function getURL(): string
    {
        return $this->ExternalUrl ?? '';
    }

    protected function FallbackTitle(): string
    {
        return $this->ExternalUrl ?: '';
    }
}
