<?php

namespace SilverStripe\AnyField\Tests\Form;

use InvalidArgumentException;
use BadMethodCallException;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use SebastianBergmann\RecursionContext\InvalidArgumentException as RecursionContextInvalidArgumentException;
use PHPUnit\Framework\ExpectationFailedException;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FormField;
use SilverStripe\AnyField\Form\AllowedClassesTrait;
use SilverStripe\AnyField\Services\AnyService;
use SilverStripe\LinkField\Models\EmailLink;
use SilverStripe\LinkField\Models\ExternalLink;
use SilverStripe\LinkField\Models\FileLink;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\LinkField\Models\PhoneLink;
use SilverStripe\LinkField\Models\SiteTreeLink;

abstract class AllowedClassesTraitTestCase extends SapphireTest
{

    /**
     * @return AllowedClassesTrait
     */
    abstract protected function getAnyField(): FormField;


    public function testBaseClass()
    {
        $field = $this->getAnyField();
        $field->setBaseClass(Link::class);
        $this->assertEquals(Link::class, $field->getBaseClass());
    }

    abstract public function testGuessBaseClass();

    public function testExcludeClasses()
    {
        $field = $this->getAnyField();

        $this->assertEmpty($field->getExcludeClasses());

        $field->addExcludedClass(Link::class);
        $this->assertEquals([Link::class], $field->getExcludeClasses());

        $field->removeExcludedClass(AnyFieldTest\SingleLink::class);
        $this->assertEquals([Link::class], $field->getExcludeClasses());

        $field->removeExcludedClass(Link::class);
        $this->assertEmpty($field->getExcludeClasses());
    }

    public function testRecursivelyAddChildClass()
    {
        $field = $this->getAnyField();
        $this->assertTrue($field->getRecursivelyAddChildClass());

        $field->setRecursivelyAddChildClass(false);
        $this->assertFalse($field->getRecursivelyAddChildClass());

        $field->setRecursivelyAddChildClass(true);
        $this->assertTrue($field->getRecursivelyAddChildClass());
    }


    public function allowedDataObjectClassesDataProvider(): array
    {
        return [
            'default set up' => [true, []],
            'non-recursive' => [false, []],
            'excluded' => [true, [Link::class]],
        ];
    }

    /**
     * @dataProvider allowedDataObjectClassesDataProvider
     */
    public function testAllowedDataObjectClasses(bool $recursive, array $excluded)
    {
        $field = $this->getAnyField();
        $field->setBaseClass(Link::class);
        $field->setRecursivelyAddChildClass($recursive);
        foreach ($excluded as $class) {
            $field->addExcludedClass($class);
        }

        $this->assertEquals(
            AnyService::singleton()->getAllowedDataObjectClasses(
                Link::class,
                $recursive,
                $excluded
            ),
            $field->getAllowedDataObjectClasses(),
            'getAllowedDataObjectClasses() relays the correct parameter to AnyService::getAllowedDataObjectClasses()'
        );
    }
}
