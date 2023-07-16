<?php

namespace SilverStripe\LinkField\Tests\Behat\Context;

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


    public function iShouldSeeALinkField(string $label)
    {
        $field = $this->getLinkField($label);
        Assert::assertNotNull($field, sprintf('HTML field "%s" not found', $label));
        return $field;
    }

    /**
     *
     * @Then /^I should see an empty "(.+?)" LinkField/
     * @param string $not
     * @param string $tabLabel
     */
    public function linkFieldShouldBeEmpty(string $label)
    {
        $field = $this->iShouldSeeALinkField($label);
        $toggle = $field->find('css', '.link-menu__toggle');

        Assert::assertSame('Add Link', $toggle->getText(), "Link field $label is not empty");
    }

    /**
     *
     * @Then /^I should see a "(.+?)" LinkField filled with "(.+?)" and a description of "(.+?)"/
     * @param string $not
     * @param string $tabLabel
     */
    public function linkFieldShouldBeContain(string $label, string $linkTitle, string $linkDescription)
    {
        $field = $this->iShouldSeeALinkField($label);
        $title = $field->find('css', '.link-title__title');
        $description = $field->find('css', '.link-title__type');

        Assert::assertSame($linkTitle, $title->getText(), "$label should contain $linkTitle");
        Assert::assertSame($linkDescription, $description->getText(), "$label should contain $linkDescription");
    }

    /**
     *
     * @Then /^I edit the "(.+?)" LinkField/
     * @param string $not
     * @param string $tabLabel
     */
    public function EditLinkField(string $label)
    {
        $field = $this->iShouldSeeALinkField($label);
        $toggle = $field->find('css', 'button.link-menu__toggle, button.link-title');

        Assert::assertNotNull($toggle);
        $toggle->click();
    }

    /**
     *
     * @Then /^I should see an option to add a "(.+?)" link to the "(.+?)" LinkField/
     * @param string $not
     * @param string $tabLabel
     */
    public function iShouldSeeAnOptionToAddLink(string $type, string $label)
    {
        $option = $this->getLinkFieldOption($label, $type);
        Assert::assertNotNull($option, "Link field $type is not there");
    }

    /**
     *
     * @Then /^I add a "(.+?)" link to the "(.+?)" LinkField/
     * @param string $not
     * @param string $tabLabel
     */
    public function iAddLinkToLinkField(string $type, string $label)
    {
        $option = $this->getLinkFieldOption($label, $type);
        $option->click();
    }

    /**
     *
     * @Then /^I should see a "(.+?)" link modal/
     * @param string $not
     * @param string $tabLabel
     */
    public function iShouldSeeLinkModal(string $type)
    {
        $modal = $this->getLinkModal();
        $title = $modal->find('css', '.modal-title');
        Assert::assertSame($type . ' Link', $title->getText(), "Link modal is not there");
    }

    /**
     * @Then /^I should see a clear button in the "(.+?)" LinkField/
     * @param string $title
     */
    public function iShouldSeeClearLinkButton(string $title): void
    {
        $this->getClearLinkButton($title);
    }

    /**
     * @Then /^I clear the "(.+?)" LinkField/
     * @param string $title
     */
    public function iClearLinkField(string $title): void
    {
        $this->getClearLinkButton($title)->click();
    }

    /**
     * Locate an HTML editor field
     *
     * @param string $locator Raw html field identifier as passed from
     */
    protected function getLinkField(string $locator): ?NodeElement
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

        $element = $page->find('css', '.link-box[data-linkfield-id=\'' . $fieldId . '\']');
        return $element;
    }

    protected function getLinkFieldOption(string $locator, string $option): ?NodeElement
    {
        $field = $this->getLinkField($locator);
        Assert::assertNotNull($option, "Link field $$locator does not exist");

        $buttons = $field->findAll('css', '.dropdown-item');
        foreach ($buttons as $button) {
            if ($button->getText() === $option . ' Link') {
                return $button;
            }
        }

        return null;
    }

    protected function getClearLinkButton(string $locator): NodeElement
    {
        $field = $this->getLinkField($locator);
        Assert::assertNotNull($field, "Link field $$locator does not exist");

        $button = $field->find('css', '.link-title__clear');
        Assert::assertNotNull($button, "Could not find clear button in $locator LinkField");

        return $button;
    }

    /**
     * @Then I should see a modal titled :title
     * @param string $title
     */
    protected function getLinkModal(): ?NodeElement
    {
        $page = $this->getMainContext()->getSession()->getPage();
        return $page->find('css', '[role=dialog]');

    }
}
