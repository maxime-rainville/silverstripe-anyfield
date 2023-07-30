<?php declare(strict_types=1);

namespace SilverStripe\AnyField\Form;

use DNADesign\Elemental\Controllers\ElementalAreaController;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\AnyField\Services\AnyService;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataObjectInterface;
use SilverStripe\ORM\SS_List;

/**
 * Allows CMS users to edit a list of links.
 */
class ManyAnyField extends JsonField
{
    protected $schemaComponent = 'ManyAnyField';

    private ?SS_List $dataList;

    use AllowedClassesTrait;

    private array $allowedDataObjectClasses = [];


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
                // If we don't have an explicitly defined list, look up the match field name on our data object.
                // Scenario: We only specified the name of the relation on the data object.
                $fieldname = $this->getName();
                $list = $data->$fieldname();
            }

            if (!empty($list)) {
                // If we managed to find something matching a sensible list, we json serialize it.
                $value = AnyService::singleton()->mapList($list);
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
        $service = AnyService::singleton();

        $value = is_string($dataValue) ? $this->parseString($dataValue) : $dataValue;

        /** @var HasMany|DataObject[] $links */
        if ($datalist = $record->$fieldname()) {

            // Loop through all the existing data objects and update/delete them as needed.
            foreach ($datalist as $do) {
                // As we process a dataobject we remove it from the value array
                $data = $this->shiftRecordByID($value, $do->ID);


                if ($data) {
                    // Update an existing record
                    if (!$do->canEdit()) {
                        Controller::curr()->httpError(403);
                    }
                    $do = $service->setData($do, $data);
                    $this->validClassName($do->ClassName);
                    $datalist->add($do);
                    $do->write();
                } else {
                    // Delete an existing record
                    if (!$do->canDelete()) {
                        Controller::curr()->httpError(403);
                    }
                    $do->delete();
                }
            }

            // Any remaining value in the array are new records that need to be created
            foreach ($value as $data) {
                // Value created in the frontend have a non-sense ID, so we remove it.
                unset($data['ID']);
                $do = Injector::inst()->create($data['dataObjectClassKey']);
                $do = $service->setData($do, $data);
                if (!$do->canCreate()) {
                    Controller::curr()->httpError(403);
                }
                $this->validClassName($do->ClassName);
                $datalist->add($do);
                $do->write();
            }
        }

        return $this;
    }

    /**
     * Find a data entry that matches the given ID, and remove it from the array
     */
    private function shiftRecordByID(array &$data, int $id): ?array
    {
        foreach ($data as $key => $item) {
            if ($item['ID'] === $id) {
                unset($data[$key]);
                return $item;
            }
        }

        return null;
    }

    /**
     * Try to guess what class we are editing
     */
    private function guessBaseClass(): ?string
    {
        $form = $this->getForm();
        if (!$form) {
            return null;
        }

        $record = $this->getForm()->getRecord();
        if (!$record) {
            return null;
        }

        $fieldname = $this->getName();

        // The name of Elemental block fields are rename with a prefix.
        if ($record instanceof BaseElement) {
            $fakeData = ElementalAreaController::removeNamespacesFromFields([$fieldname => 0], $record->ID);
            $fakeData = array_flip($fakeData);
            $fieldname = $fakeData[0];
        };

        $class = DataObject::getSchema()->hasManyComponent(get_class($record), $fieldname);
        return $class;

    }

    public function InputValue(): string
    {
        $value = $this->Value();

        if ($value instanceof SS_List) {
            $value = AnyService::singleton()->jsonSerializeList($value);
        } elseif (is_array($value)) {
            $value = json_encode($value);
        }

        return $value;
    }
}
