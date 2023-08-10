<?php

namespace SilverStripe\AnyField\Tests\Behat\Context;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use PHPUnit\Framework\Assert;
use SilverStripe\BehatExtension\Context\FixtureContext as BaseFixtureContext;
use SilverStripe\BehatExtension\Utility\StepHelper;
use SilverStripe\MinkFacebookWebDriver\FacebookWebDriver;
use SilverStripe\Versioned\ChangeSet;

trait ManyAnyFieldContextTrait
{

    public function iShouldSeeManyAnyField(string $label)
    {
        $field = $this->getManyAnyField($label);
        Assert::assertNotNull($field, sprintf('HTML field "%s" not found', $label));
        return $field;
    }

    /**
     *
     * @Then /^I should see an empty "(.+?)" ManyAnyField/
     * @param string $not
     * @param string $tabLabel
     */
    public function ManyAnyFieldShouldBeEmpty(string $label)
    {
        $field = $this->iShouldSeeManyAnyField($label);
        $items = $this->getManyAnyChildItems($field);

        Assert::assertEmpty($items, "ManyAnyField field $label is not empty");
    }

    /**
     * @Then /^I should see a "(.+?)" ManyAnyField filled with "(.+?)" and a description of "(.+?)" on position ([0-9]+)/
     * @param string $not
     * @param string $tabLabel
     */
    public function ManyAnyFieldShouldBeContain(string $label, string $title, string $description, int $pos)
    {
        $field = $this->iShouldSeeManyAnyField($label);
        $items = $this->getManyAnyChildItems($field);
        $item = $items[$pos - 1];

        $titleNode = $item->find('css', '.any-picker-title__title');
        /** @var NodeElement $description */
        $descriptionNode = $item->find('css', '.any-picker-title__type');

        Assert::assertSame($title, $titleNode->getText(), "$label should contain $title");
        Assert::assertSame($description, $descriptionNode->getText(), "$label should contain $description");
    }

    /**
     *
     * @Then /^I edit the "(.+?)" ManyAnyField/
     * @param string $not
     * @param string $tabLabel
     */
    public function EditManyAnyField(string $label)
    {
        $field = $this->iShouldSeeManyAnyField($label);
        $toggle = $field->find('css', 'button.any-picker-menu__toggle');

        Assert::assertNotNull($toggle);
        $toggle->click();
    }

    /**
     *
     * @Then /^I should see an option to add a "(.+?)" item to the "(.+?)" ManyAnyField/
     * @param string $not
     * @param string $tabLabel
     */
    public function iShouldSeeAnOptionToAddItemToManyAnyField(string $type, string $label)
    {
        $option = $this->getManyAnyFieldOption($label, $type);
        Assert::assertNotNull($option, "ManyAnyField $type is not there");
    }

    /**
     *
     * @Then /^I add a "(.+?)" item to the "(.+?)" ManyAnyField/
     * @param string $not
     * @param string $tabLabel
     */
    public function iAddItemToManyAnyField(string $type, string $label)
    {
        $option = $this->getManyAnyFieldOption($label, $type);
        $option->click();
    }

    /**
     * @Then /^I should see a clear button in the "(.+?)" ManyAnyField/
     * @param string $title
     */
    public function iShouldSeeClearButtonInManyAnyField(string $title): void
    {
        $this->getClearButton($title);
    }

    /**
     * @Then /^I clear the "(.+?)" ManyAnyField/
     * @param string $title
     */
    public function iClearManyAnyField(string $title): void
    {
        $this->getManyAnyFieldClearButton($title)->click();
    }

    /**
     * Locate an HTML editor field
     *
     * @param string $locator Raw html field identifier as passed from
     */
    protected function getManyAnyField(string $locator): ?NodeElement
    {
        $locator = str_replace('\\"', '"', $locator ?? '');
        $page = $this->getMainContext()->getSession()->getPage();
        $input = $page->find('css', 'input[name=\'' . $locator . '\']');
        $fieldId = null;

        if ($input) {
            // First lets try to find the hidden input
            $fieldId = $input->getAttribute('id');
        } else {
            // Then let's try to find the label
            $label = $page->findAll('xpath', sprintf('//label[normalize-space()=\'%s\']', $locator));
            if (!empty($label)) {
                Assert::assertCount(1, $label, "Found more than one element containing the phrase \"$locator\"");
                $label = array_shift($label);
                $fieldId = $label->getAttribute('for');
            }
        }

        if (empty($fieldId)) {
            return null;
        }

        $element = $page->find('css', '[data-manyanyfield-id=\'' . $fieldId . '\']');
        return $element;
    }

    /**
     * @return NodeElement[]
     */
    protected function getManyAnyChildItems(NodeElement $field): array
    {
        return $field->findAll('css', '.multi-any-picker__list .any-picker-title');
    }

    protected function getManyAnyFieldOption(string $locator, string $option): ?NodeElement
    {
        $field = $this->getManyAnyField($locator);
        Assert::assertNotNull($option, "ManyAnyField field $$locator does not exist");

        $buttons = $field->findAll('css', '.dropdown-item');
        foreach ($buttons as $button) {
            if ($button->getText() === $option) {
                return $button;
            }
        }

        return null;
    }

    protected function getManyAnyClearButton(string $locator): NodeElement
    {
        $field = $this->getAnyField($locator);
        Assert::assertNotNull($field, "AnyField $$locator does not exist");

        $button = $field->find('css', '.any-picker-title__clear');
        Assert::assertNotNull($button, "Could not find clear button in $locator AnyField");

        return $button;
    }

}
