<?php

namespace SilverStripe\AnyField\Tests\Behat\Context;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use PHPUnit\Framework\Assert;
use SilverStripe\BehatExtension\Context\FixtureContext as BaseFixtureContext;
use SilverStripe\BehatExtension\Utility\StepHelper;
use SilverStripe\MinkFacebookWebDriver\FacebookWebDriver;
use SilverStripe\Versioned\ChangeSet;

/**
 * Context used to create fixtures in the SilverStripe ORM.
 */
class FixtureContext extends BaseFixtureContext
{


    public function iShouldSeeAAnyField(string $label)
    {
        $field = $this->getAnyField($label);
        Assert::assertNotNull($field, sprintf('HTML field "%s" not found', $label));
        return $field;
    }

    /**
     *
     * @Then /^I should see an empty "(.+?)" AnyField/
     * @param string $not
     * @param string $tabLabel
     */
    public function AnyFieldShouldBeEmpty(string $label)
    {
        $field = $this->iShouldSeeAAnyField($label);
        $toggle = $field->find('css', '.any-picker-menu__toggle');

        Assert::assertSame('Add Link', $toggle->getText(), "AnyField field $label is not empty");
    }

    /**
     *
     * @Then /^I should see a "(.+?)" AnyField filled with "(.+?)" and a description of "(.+?)"/
     * @param string $not
     * @param string $tabLabel
     */
    public function AnyFieldShouldBeContain(string $label, string $title, string $description)
    {
        $field = $this->iShouldSeeAAnyField($label);
        $titleNode = $field->find('css', '.any-picker-title__title');
        /** @var NodeElement $description */
        $descriptionNode = $field->find('css', '.any-picker-title__type');

        Assert::assertSame($title, $titleNode->getText(), "$label should contain $title");
        Assert::assertSame($description, $descriptionNode->getText(), "$label should contain $description");
    }

    /**
     *
     * @Then /^I edit the "(.+?)" AnyField/
     * @param string $not
     * @param string $tabLabel
     */
    public function EditAnyField(string $label)
    {
        $field = $this->iShouldSeeAAnyField($label);
        $toggle = $field->find('css', 'button.any-picker-menu__toggle, button.any-picker-title');

        Assert::assertNotNull($toggle);
        $toggle->click();
    }

    /**
     *
     * @Then /^I should see an option to add a "(.+?)" item to the "(.+?)" AnyField/
     * @param string $not
     * @param string $tabLabel
     */
    public function iShouldSeeAnOptionToAddItem(string $type, string $label)
    {
        $option = $this->getAnyFieldOption($label, $type);
        Assert::assertNotNull($option, "AnyField $type is not there");
    }

    /**
     *
     * @Then /^I add a "(.+?)" item to the "(.+?)" AnyField/
     * @param string $not
     * @param string $tabLabel
     */
    public function iAddItemToAnyField(string $type, string $label)
    {
        $option = $this->getAnyFieldOption($label, $type);
        $option->click();
    }

    /**
     *
     * @Then /^I should see a "(.+?)" AnyField modal/
     * @param string $not
     * @param string $tabLabel
     */
    public function iShouldSeeModal(string $type)
    {
        $modal = $this->getModal();
        $title = $modal->find('css', '.modal-title');
        Assert::assertSame($type, $title->getText(), "AnyField modal is not there");
    }

    /**
     * @Then /^I should see a clear button in the "(.+?)" AnyField/
     * @param string $title
     */
    public function iShouldSeeClearButton(string $title): void
    {
        $this->getClearButton($title);
    }

    /**
     * @Then /^I clear the "(.+?)" AnyField/
     * @param string $title
     */
    public function iClearAnyField(string $title): void
    {
        $this->getClearButton($title)->click();
    }

    /**
     * Locate an HTML editor field
     *
     * @param string $locator Raw html field identifier as passed from
     */
    protected function getAnyField(string $locator): ?NodeElement
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

        $element = $page->find('css', '.any-field-box[data-anyfield-id=\'' . $fieldId . '\']');
        return $element;
    }

    protected function getAnyFieldOption(string $locator, string $option): ?NodeElement
    {
        $field = $this->getAnyField($locator);
        Assert::assertNotNull($option, "AnyField field $$locator does not exist");

        $buttons = $field->findAll('css', '.dropdown-item');
        foreach ($buttons as $button) {
            if ($button->getText() === $option) {
                return $button;
            }
        }

        return null;
    }

    protected function getClearButton(string $locator): NodeElement
    {
        $field = $this->getAnyField($locator);
        Assert::assertNotNull($field, "AnyField $$locator does not exist");

        $button = $field->find('css', '.any-picker-title__clear');
        Assert::assertNotNull($button, "Could not find clear button in $locator AnyField");

        return $button;
    }

    /**
     * @Then I should see a modal titled :title
     * @param string $title
     */
    protected function getModal(): ?NodeElement
    {
        $page = $this->getMainContext()->getSession()->getPage();
        return $page->find('css', '[role=dialog]');
    }
}
