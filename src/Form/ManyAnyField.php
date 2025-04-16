<?php declare(strict_types=1);

namespace SilverStripe\AnyField\Form;

use DNADesign\Elemental\Forms\EditFormFactory;
use DNADesign\Elemental\Models\BaseElement;
use LogicException;
use Psr\Container\NotFoundExceptionInterface;
use SilverStripe\AnyField\Services\AnyService;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPResponse_Exception;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Validation\ValidationException;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Model\List\SS_List;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataObjectInterface;

/**
 * Allows CMS users to edit a list of links.
 */
class ManyAnyField extends JsonField
{
    use AllowedClassesTrait;

    protected $schemaComponent = 'ManyAnyField';

    private ?SS_List $dataList;

    private ?string $sortColumn = null;

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
    public function setList(SS_List $list): self
    {
        $this->dataList = $list;

        return $this;
    }

    /**
     * Define what column the list should be sorted by. Set to a falsy value to disable sorting.
     */
    public function setSort(?string $sortColumn): self
    {
        $this->sortColumn = $sortColumn;

        return $this;
    }

    /**
     * Retrieve the name of the column use for sorting or null if sorting is disabled.
     */
    public function getSort(): ?string
    {
        return $this->sortColumn;
    }

    public function getList(): ?SS_List
    {
        return $this->dataList;
    }

    public function setValue($value, $data = null)
    {
        if (is_string($value) && $value) {
            $value = $this->parseString($value);
        } elseif ($value instanceof SS_List) {
            // If the value is a list, we convert it to a JSON string with all our link data.
            // Scenario: We're about to render the data for the front end
            $value = AnyService::singleton()->mapList($value);
        } elseif (!$value) {
            // If the value is empty, we convert our list to a JSON string with all our link data.
            // Scenario: We're about to render the data for the front end
            $list = $this->getList();
            if (!$list && $data) {
                // If we don't have an explicitly defined list, look up the match field name on our data object.
                // Scenario: We only specified the name of the relation on the data object.
                $fieldName = $this->getName();
                $list = $data->$fieldName();
            }

            if ($list) {
                // If we managed to find something matching a sensible list, we json serialize it.
                $value = AnyService::singleton()->mapList($list);
            }
        }
        // If value is not falsy, that means we got some JSON data back from the frontend.

        return parent::setValue($value, $data);
    }

    /**
     * @param DataObjectInterface|DataObject $record
     * @return ManyAnyField
     * @throws HTTPResponse_Exception
     * @throws NotFoundExceptionInterface
     * @throws ValidationException
     */
    public function saveInto(DataObjectInterface $record)
    {
        // Check required relation details are available
        $fieldName = $this->getName();

        if (!$fieldName) {
            return $this;
        }

        $dataValue = $this->dataValue();
        $service = AnyService::singleton();
        $value = is_string($dataValue) ? $this->parseString($dataValue) : $dataValue;

        if ($this->isSortable($record)) {
            $sortOrder = $this->buildSortOrder($value);
            $sortColumn = $this->getSort();

            foreach ($value as &$item) {
                $item[$sortColumn] = $sortOrder[$item['ID']];
            }
        }

        $dataList = $record->$fieldName();

        if ($dataList) {
            // Loop through all the existing data objects and update/delete them as needed.
            foreach ($dataList as $model) {
                // As we process a data object we remove it from the value array
                $data = $this->shiftRecordByID($value, $model->ID);

                if ($data) {
                    // Update an existing record
                    if (!$model->canEdit()) {
                        Controller::curr()->httpError(403);
                    }

                    $model = $service->setData($model, $data);
                    $this->validClassName($model->ClassName, $record);
                    $dataList->add($model);
                    $model->write();
                } else {
                    // Delete an existing record
                    if (!$model->canDelete()) {
                        Controller::curr()->httpError(403);
                    }

                    $model->delete();
                }
            }

            // Any remaining value in the array are new records that need to be created
            foreach ($value as $data) {
                // Value created in the frontend have a non-sense ID, so we remove it.
                unset($data['ID']);

                /** @var DataObject $model */
                $model = Injector::inst()->create($data['dataObjectClassKey']);
                $model = $service->setData($model, $data);

                if (!$model->canCreate()) {
                    Controller::curr()->httpError(403);
                }

                $this->validClassName($model->ClassName, $record);
                $dataList->add($model);
                $model->write();
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
     * Build the sort order for the list of data objects. Returns a list of IDs with their matching sort order.
     */
    private function buildSortOrder(array $value): array
    {
        // Build the list of IDs and the sorted order
        $map = array_map(function ($item) {
            return $item['ID'];
        }, $value);

        // We want our first key to be 1, so we don't have a sort value of 0
        array_unshift($map, "phoney");
        unset($map[0]);

        // Flip it so the ID returns its order
        return array_flip($map);
    }

    /**
     * Try to guess what class we are editing
     *
     * @param DataObjectInterface|null $record
     * @return string|null
     * @throws NotFoundExceptionInterface
     */
    protected function guessBaseClass(?DataObjectInterface $record = null): ?string
    {
        if (!$record) {
            $form = $this->getForm();

            if (!$form) {
                return null;
            }

            $record = $form->getRecord();

            if (!$record) {
                return null;
            }
        }

        $fieldName = $this->getName();

        // Elemental sometimes rename our record field to something else.
        // This bit figures out what the name is meant to be
        if (class_exists(BaseElement::class) && is_a($record, BaseElement::class)) {
            /** @var EditFormFactory $factory */
            $factory = Injector::inst()->get(EditFormFactory::class);

            // Get updated name of the form field
            $field = TextField::create($fieldName);
            $fields = FieldList::create([$field]);
            $factory->removeNamespaceFromFields($fields, ['Record' => $record]);
            $fieldName = $field->getName();
        }

        return DataObject::getSchema()->hasOneComponent($record::class, $fieldName);
    }

    public function InputValue(): string
    {
        $value = $this->getValue();

        if ($value instanceof SS_List) {
            $value = AnyService::singleton()->jsonSerializeList($value);
        } elseif (is_array($value)) {
            $value = json_encode($value);
        }

        return $value ?: '';
    }

    protected function parseString(string $value): ?array
    {
        $value = parent::parseString($value);

        // Recast any falsy value to an empty array
        return $value ?: [];
    }

    /**
     * Check if the Field can be sorted.
     *
     * @param DataObject|null $record
     * @return bool
     * @throws LogicException If the sorting column is invalid
     * @throws NotFoundExceptionInterface
     */
    private function isSortable(?DataObject $record = null): bool
    {
        $sort = $this->getSort();

        if ($sort) {
            $baseClass = $this->getBaseClass($record);
            $list = DataObject::get($baseClass);

            if (!$list->canSortBy($sort)) {
                $message = sprintf('ManyAnyField: Cannot sort by %s on %s', $sort, $baseClass);
                throw new LogicException($message);
            }

            return true;
        }

        return false;
    }

    /**
     * @return array
     * @throws NotFoundExceptionInterface
     */
    public function getProps(): array
    {
        $props = parent::getProps();
        $allowedClassProps = $this->getAllowedClassProps();

        $props['sortable'] = $this->isSortable();

        return array_merge($props, $allowedClassProps);
    }
}
