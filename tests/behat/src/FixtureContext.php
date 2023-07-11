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
    use StepHelper;


}
