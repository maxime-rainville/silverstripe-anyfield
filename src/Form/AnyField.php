<?php declare(strict_types=1);

namespace SilverStripe\AnyField\Form;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataObject;

/**
 * Allows CMS users to edit a DataObject.
 */
class AnyField extends JsonField
{
    protected $schemaComponent = 'AnyField';

    private array $allowedDataObjectClasses = [];

    public function __construct(string $name, string $title = null, $value = null)
    {
        parent::__construct($name, $title, $value);
    }

    public function setValue($value, $data = null)
    {
        return parent::setValue($value, $data);
    }

    public function addAllowedDataObjectClass(string $className, bool $recursive=true, bool $includeBaseClass=true): self
    {
        if (!$recursive && !$includeBaseClass) {
            throw new \InvalidArgumentException('Cannot add a type to anyfield without recursion and excluding the top level');
        }

        $singleton = DataObject::singleton($className);

        if (!$singleton) {
            throw new \InvalidArgumentException($className . ' is not a valid DataObject class and cannot be managed by an AnyField');
        }

        if (!$recursive) {

        } else {
            $classes = ClassInfo::subclassesFor($className, $includeBaseClass);
            foreach ($classes as $class) {
                $this->addSpecificClass($class);
            }
        }

        $this->props['allowedDataObjectClasses'] = $this->allowedDataObjectClasses;

        return $this;
    }

    private function addSpecificClass(string $className)
    {
        $singleton = DataObject::singleton($className);
        $this->allowedDataObjectClasses[$className] = [
            'key' => $className,
            'title' => $singleton->i18n_singular_name(),
            'icon' => $singleton->config()->get('icon_class'),
            'handlerName' => $singleton->config()->get('modal_handler'),
        ];
    }

    public function removeAllowedDataObjectClass(string $className): self
    {
        $this->allowedDataObjectClasses = array_diff($this->allowedDataObjectClasses, [$className]);

        $this->props['allowedDataObjectClasses'] = $this->allowedDataObjectClasses;

        return $this;
    }

    public function getAllowedDataObjectClasses(): array
    {
        return $this->allowedDataObjectClasses;
    }

    public function getPropsJSON(): string
    {
        // Try to auto detect what can go in our field given the name of the field
        if (empty($this->allowedDataObjectClasses)) {
            throw new \InvalidArgumentException('AnyField must have at least one allowed DataObject class');
        }

        return parent::getPropsJSON();
    }
}
