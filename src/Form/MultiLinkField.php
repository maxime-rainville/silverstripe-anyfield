<?php declare(strict_types=1);

namespace SilverStripe\LinkField\Form;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataObjectInterface;
use SilverStripe\ORM\SS_List;

/**
 * Allows CMS users to edit a list of links.
 */
class MultiLinkField extends JsonField
{
    protected $schemaComponent = 'MultiLinkField';

    private ?SS_List $dataList;

    public function __construct($name, $title = null, SS_List $dataList = null)
    {
        parent::__construct($name, $title, null);
        $this->dataList = $dataList;
    }

    /**
     * Set the data source.
     *
     * @param SS_List $list
     *
     * @return $this
     */
    public function setList(SS_List $list)
    {
        $this->dataList = $list;
        return $this;
    }

    /**
     *
     */
    public function getList(): ?SS_List
    {
        return $this->dataList;
    }

    public function setValue($value, $data = null)
    {
        if (empty($value)) {
            // If the value is empty, we convert our list to a JSON string with all our link data.
            // Scenario: We're about to render the data for the front end
            $list = $this->getList();
            if (empty($list) && !empty($data)) {
                // If we don't have an explicitly defined list, look up the match filed name on our data object.
                // Scenario: We only specified the name of the relation on the data object.
                $fieldname = $this->getName();
                $list = $data->$fieldname();
            }

            if (!empty($list)) {
                // If we managed to find something matching a sensible list, we json serialize it.
                $value = json_encode(
                    array_map(function (Link $link) {
                        return $link->jsonSerialize();
                    }, $list->toArray())
                );
            }
        }
        // If value is not falsy, that means we got some JSON data back from the frontend.

        return parent::setValue($value, $data);
    }

    /**
     * @param DataObject|DataObjectInterface $record
     * @return $this
     */
    public function saveInto(DataObjectInterface $record)
    {
        // Check required relation details are available
        $fieldname = $this->getName();
        if (!$fieldname) {
            return $this;
        }

        $dataValue = $this->dataValue();
        $value = is_string($dataValue) ? $this->parseString($dataValue) : $dataValue;

        /** @var HasMany|Link[] $links */
        if ($links = $record->$fieldname()) {
            foreach ($links as $linkDO) {
                $linkData = $this->shiftLinkDataByID($value, $linkDO->ID);
                if ($linkData) {
                    $linkDO->setData($linkData);
                    $linkDO->write();
                } else {
                    $linkDO->delete();
                }
            }

            foreach ($value as $linkData) {
                unset($linkData['ID']);
                $linkDO = Link::create();
                $linkDO = $linkDO->setData($linkData);
                $links->add($linkDO);
                $linkDO->write();
            }
        }

        return $this;
    }

    /**
     * Find a data entry that matches the given ID, and remove it from the array
     */
    private function shiftLinkDataByID(array &$linkData, int $id): ?array
    {
        foreach ($linkData as $key => $link) {
            if ($link['ID'] === $id) {
                unset($linkData[$key]);
                return $link;
            }
        }

        return null;
    }
}
