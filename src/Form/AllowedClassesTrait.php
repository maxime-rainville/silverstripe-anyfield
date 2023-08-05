<?php declare(strict_types=1);

namespace SilverStripe\AnyField\Form;

use BadMethodCallException;
use Psr\Container\NotFoundExceptionInterface;
use InvalidArgumentException;
use SilverStripe\AnyField\Services\AnyService;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataObjectInterface;

/**
 * Encapsulate some shared logic between AnyField and ManyAnyField for tracking base classes
 */
trait AllowedClassesTrait
{

    private string $baseClass = '';
    private bool $recursivelyAddChildClass = true;
    private array $excludedClasses = [];

    /**
     * Retrieve the current BaseClass. If the BaseClass has not been explicitly set, try to guess what it is by looking
     * at the relation the field is pointed at. Returns a blank string if the BaseClass can not be ascertained.
     */
    public function getBaseClass(?DataObjectInterface $record = null): string
    {
        if ($this->baseClass) {
            return $this->baseClass;
        }

        return (string)$this->guessBaseClass($record);
    }


    /**
     * Explicitly set the BaseClass for this any Field
     * @throws InvalidArgumentException If $className is not a valid DataObject class.
     */
    public function setBaseClass(string $className): self
    {
        $singleton = DataObject::singleton($className);

        if (!$singleton) {
            throw new \InvalidArgumentException(
                $className . ' is not a valid DataObject class and cannot be managed by an AnyField'
            );
        }

        $this->baseClass = $className;

        return $this;
    }

    /**
     * Exclude a specific class from the allowed DataObject class that this
     * Field can create even if this class is a child class of the base class.
     */
    public function addExcludedClass(string $className): self
    {
        $this->excludedClasses[] = $className;
        return $this;
    }

    /**
     * Retrieve the list of excluded classes that this field can not create.
     */
    public function getExcludeClasses(): array
    {
        return $this->excludedClasses;
    }

    /**
     * Remove a class from the list of excluded classes.
     */
    public function removeExcludedClass(string $className): self
    {
        $this->excludedClasses = array_filter($this->excludedClasses, function ($class) use ($className) {
            return $class !== $className;
        });

        return $this;
    }

    /**
     * Whether child classes of the base class should automatically be added
     * to the list of allowed classes.
     */
    public function getRecursivelyAddChildClass(): bool
    {
        return $this->recursivelyAddChildClass;
    }

    /**
     * Set whether child classes of the base class should automatically be
     *  added to the list of allowed classes.
     */
    public function setRecursivelyAddChildClass(bool $recursivelyAddChildClass): self
    {
        $this->recursivelyAddChildClass = $recursivelyAddChildClass;
        return $this;
    }

    /**
     * Try to guess the base class for our any field
     * @param null|DataObjectInterface $record
     * @return null|string
     */
    abstract protected function guessBaseClass(?DataObjectInterface $record = null): ?string;

    public function getAllowedDataObjectClasses(?DataObjectInterface $record = null): array
    {
        $baseClass = $this->getBaseClass($record);

        return AnyService::singleton()->getAllowedDataObjectClasses(
            $baseClass,
            $this->getRecursivelyAddChildClass(),
            $this->getExcludeClasses()
        );
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $baseClass = $this->getBaseClass();

        $allowedDataObjectClasses = $this->getAllowedDataObjectClasses();
        if (empty($allowedDataObjectClasses)) {
            $path = explode('\\', __CLASS__);
            throw new \InvalidArgumentException(
                sprintf('%s must have at least one allowed DataObject class', array_pop($path))
            );
        }

        $props['allowedDataObjectClasses'] = $allowedDataObjectClasses;
        $singleton = DataObject::singleton($baseClass);
        $props['baseDataObjectName'] = $singleton->i18n_singular_name();
        $props['baseDataObjectIcon'] = $singleton->config()->get('icon');

        return $props;
    }

    protected function validClassName(string $className, ?DataObjectInterface $record = null): void
    {
        $valid = array_keys($this->getAllowedDataObjectClasses($record));
        if (!in_array($className, $valid)) {
            throw new \InvalidArgumentException(sprintf(
                '%s is not a valid DataObject class for this field. Valid classes are: %s',
                $className,
                implode(', ', $valid)
            ));
        }
    }
}
