<?php declare(strict_types=1);

namespace SilverStripe\LinkField\Models;

use SilverStripe\Assets\File;

/**
 * A link to a File track in asset-admin
 *
 * @property File $File
 * @property int $FileID
 */
class FileLink extends Link
{
    private static string $table_name = 'LinkField_FileLink';

    private static array $has_one = [
        'File' => File::class,
    ];

    private static $icon = 'menu-files';

    public function generateLinkDescription(array $data): array
    {
        $description = '';
        $title = empty($data['Title']) ? '' : $data['Title'];

        if (!empty($data['FileID'])) {
            $file = File::get()->byID($data['FileID']);
            if ($file) {
                $description = $file->getFilename();
                if (empty($title)) {
                    $title = $file->Title;
                }
            }
        }

        return [
            'title' => $title,
            'description' => $description
        ];
    }

    public function LinkTypeHandlerName(): string
    {
        return 'InsertMediaModal';
    }

    public function getURL(): string
    {
        return $this->File?->getURL() ?? '';
    }

    protected function FallbackTitle(): string
    {
        return $this->File ? $this->File->Title : '';
    }
}
