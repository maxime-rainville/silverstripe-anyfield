<?php declare(strict_types=1);

namespace SilverStripe\AnyField\Form;

use BadMethodCallException;
use Psr\Container\NotFoundExceptionInterface;
use InvalidArgumentException;
use SilverStripe\AnyField\Services\DataObjectClassInfo;
use SilverStripe\Core\ClassInfo;
use SilverStripe\ORM\DataObject;

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
    public function getBaseClass(): string
    {
        if ($this->baseClass) {
            return $this->baseClass;
        }

        return (string)$this->guessBaseClass();
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

    private abstract function guessBaseClass(): ?string;

    public function getAllowedDataObjectClasses(): array
    {
        $baseClass = $this->getBaseClass();

        return DataObjectClassInfo::singleton()->getAllowedDataObjectClasses(
            $baseClass,
            $this->getRecursivelyAddChildClass(),
            $this->getExcludeClasses()
        );
    }
}
