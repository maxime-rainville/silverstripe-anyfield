<?php

namespace SilverStripe\AnyField\Tests\Behat\Context;

use SilverStripe\BehatExtension\Context\FixtureContext as BaseFixtureContext;
use PHPUnit\Framework\Assert;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use SilverStripe\BehatExtension\Utility\StepHelper;

/**
 * Context used to create fixtures in the SilverStripe ORM.
 */
class FixtureContext extends BaseFixtureContext
{

    use AnyFieldContextTrait;
    use ManyAnyFieldContextTrait;
    use StepHelper;


    /**
     *
     * @Then /^I should see a "(.+?)" (Many)?AnyField modal/
     * @param string $not
     * @param string $tabLabel
     */
    public function iShouldSeeModal(string $type, string $many = '')
    {
        $modal = $this->getModal();
        $title = $modal->find('css', '.modal-title');
        Assert::assertSame($type, $title->getText(), "{$many}AnyField modal is not there");
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

    /**
     * Select a value in the anchor selector field
     *
     * @When /^I select "([^"]*)" in the "([^"]*)" anchor dropdown$/
     */
    public function iSelectValueInAnchorDropdown($text, $selector)
    {
        $page = $this->getMainContext()->getSession()->getPage();
        /** @var NodeElement $parentElement */
        $parentElement = null;
        $this->retryThrowable(function () use (&$parentElement, &$page, $selector) {
            $parentElement = $page->find('css', $selector);
            Assert::assertNotNull($parentElement, sprintf('"%s" element not found', $selector));
            $page = $this->getMainContext()->getSession()->getPage();
        });

        $this->retryThrowable(function () use ($parentElement, $selector) {
            $dropdown = $parentElement->find('css', '.anchorselectorfield__dropdown-indicator');
            Assert::assertNotNull($dropdown, sprintf('Unable to find the dropdown in "%s"', $selector));
            $dropdown->click();
        });

        $this->retryThrowable(function () use ($text, $parentElement, $selector) {
            $element = $parentElement->find('xpath', sprintf('//*[count(*)=0 and .="%s"]', $text));
            Assert::assertNotNull($element, sprintf('"%s" not found in "%s"', $text, $selector));
            $element->click();
        });
    }
}
