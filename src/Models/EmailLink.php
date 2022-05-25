<?php declare(strict_types=1);

namespace SilverStripe\LinkField\Models;

use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;

/**
 * A link to an Email address.
 *
 * @property string $Email
 */
class EmailLink extends Link
{
    private static string $table_name = 'LinkField_EmailLink';

    private static array $db = [
        'Email' => 'Varchar(255)',
    ];

    public function generateLinkDescription(array $data): array
    {
        $description = isset($data['Email']) ? $data['Email'] : '';
        $title = empty($data['Title']) ? $description : $data['Title'];
        return [
            'title' => $title,
            'description' => $description
        ];
    }

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->replaceField('Email', EmailField::create('Email'));

        return $fields;
    }

    public function getURL(): string
    {
        return $this->Email ? sprintf('mailto:%s', $this->Email) : '';
    }

    protected function FallbackTitle(): string
    {
        return $this->Email ?: '';
    }
}
