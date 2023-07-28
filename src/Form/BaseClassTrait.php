<?php declare(strict_types=1);

namespace SilverStripe\AnyField\Form;

use SilverStripe\AnyField\Services\DataObjectClassInfo;
use SilverStripe\Core\ClassInfo;
use SilverStripe\ORM\DataObject;

trait BaseClassTrait
{
    private string $baseClass = '';
    private bool $recursivelyAddChildClass = true;
    private array $excludedClasses = [];

    public function getBaseClass(): string
    {
        if ($this->baseClass) {
            return $this->baseClass;
        }

        return (string)$this->guessBaseClass();
    }

    public function setBaseClass(string $className): self
    {
        $singleton = DataObject::singleton($className);

        if (!$singleton) {
            throw new \InvalidArgumentException($className . ' is not a valid DataObject class and cannot be managed by an AnyField');
        }

        $this->baseClass = $className;

        return $this;
    }

    public function addExcludedClass(string $className): self
    {
        $this->excludedClasses[] = $className;
        return $this;
    }

    public function getExcludeClasses(): array
    {
        return $this->excludedClasses;
    }

    public function removeExcludedClass(string $className): self
    {
        $this->excludedClasses = array_filter($this->excludedClasses, function ($class) use ($className) {
            return $class !== $className;
        });

        return $this;
    }

    public function getRecursivelyAddChildClass(): bool
    {
        return $this->recursivelyAddChildClass;
    }

    public function setRecursivelyAddChildClass(bool $recursivelyAddChildClass): self
    {
        $this->recursivelyAddChildClass = $recursivelyAddChildClass;
        return $this;
    }

    private abstract function guessBaseClass(): ?string;
}
