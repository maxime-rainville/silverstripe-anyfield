<?php declare(strict_types=1);

namespace SilverStripe\LinkField\Models;

/**
 * A link to a phone number
 *
 * @property string $Phone
 */
class PhoneLink extends Link
{
    private static string $table_name = 'LinkField_PhoneLink';

    private static array $db = [
        'Phone' => 'Varchar(255)',
    ];

    public function generateLinkDescription(array $data): array
    {
        if (isset($data)) {
            return [
                'title' => $data['Phone']
            ];
        }

        return [];
    }

    public function getURL(): string
    {
        return $this->Phone ? sprintf('tel:%s', $this->Phone) : '';
    }
}
