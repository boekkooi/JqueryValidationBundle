<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\WebAssert;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext, MinkAwareContext
{
    /**
     * @var bool
     */
    private $serverSide;

    public function __construct($serverSide = false)
    {
        $this->serverSide = $serverSide;
    }

    /**
     * @When /^I fill in the "(?P<formName>[^"]*)" form with the following:$/
     */
    public function iFillInTheFormWithTheFollowing($formName, TableNode $table)
    {
        $page = $this->getSession()->getPage();

        $this->assertSession()->elementExists('css', sprintf('form[name="%s"]', $formName));

        foreach ($table->getRows() as $row) {
            $fieldSelector = $this->resolveFieldSelector($row[0], $formName);
            $this->assertSession()->elementExists('css', $fieldSelector);

            $page->find('css', $fieldSelector)->setValue($row[1]);
        };
    }

    /**
     * @When /^I fill in the "(?P<formName>[^"]*)" form field with a string of length (?P<length>\d+) the following:$/
     */
    public function iFillInTheFormFieldWithAStringOfLengthTheFollowing($formName, $length, TableNode $table)
    {
        $fieldAndValueTable = array();
        foreach ($table->getRows() as $row) {
            $fieldAndValueTable[] = array(
                $row[0],
                str_repeat('a', $length)
            );
        }

        $this->iFillInTheFormWithTheFollowing($formName, new TableNode($fieldAndValueTable));
    }

    /**
     * @Given /^I submit "(?P<formName>[^"]*)"(?:| by clicking on "(?P<submitButton>[^"]*)")$/
     */
    public function iSubmit($formName, $submitButton = 'submit')
    {
        $page = $this->getSession()->getPage();

        $formSelector = sprintf('form[name="%s"]', $formName);
        $this->assertSession()->elementExists('css', $formSelector);

        if ($this->serverSide) {
            $page->find('css', '#disable_js_validation')->click();
        }

        $formElt = $page->find('css', $formSelector);
        if (($button = $formElt->findButton($submitButton)) !== null) {
            $button->press();
            return;
        }

        $buttonSelector = $this->resolveFieldSelector($submitButton, $formName);
        $this->assertSession()->elementExists('css', $buttonSelector);

        $page->find('css', $buttonSelector)->click();
    }

    /**
     * @Then /^I should (?P<strict>|only )see the following validation errors in the "(?P<formName>[^"]*)" form:$/
     */
    public function iShouldSeeTheFollowingValidationErrorsInForm($formName, $strict, TableNode $table)
    {
        $this->onTheCorrectSide();

        $page = $this->getSession()->getPage();
        $strict = !empty($strict);

        $formSelector = sprintf('form[name="%s"]', $formName);
        $this->assertSession()->elementExists('css', $formSelector);

        $fields = array();
        $expectedErrorCount = 0;
        foreach ($table->getRows() as $row) {
            $fieldSelector = $this->resolveFieldSelector($row[0], $formName);
            $this->assertSession()->elementExists('css', $fieldSelector);

            $fields[$fieldSelector][] = $row[1];
            $expectedErrorCount++;
        }

        foreach ($fields as $fieldSelector => $expectedErrors) {
            $fieldElt = $page->find('css', $fieldSelector);

            /** @var \Behat\Mink\Element\NodeElement[] $fieldErrorElts */
            $fieldErrorElts = $page->findAll(
                'css',
                sprintf('#%s-errors li', $fieldElt->getAttribute('id'))
            );

            $fieldErrors = array();
            foreach ($fieldErrorElts as $fieldErrorElt) {
                $fieldErrors[] = $fieldErrorElt->getText();
            }
            \PHPUnit_Framework_Assert::assertEquals($expectedErrors, $fieldErrors, sprintf('Invalid field \'%s\' errors', $fieldSelector));
        }

        if ($strict) {
            $formErrorElts = $page->findAll(
                'css',
                sprintf('%s ul[id$="-errors"] li.error', $formSelector)
            );

            \PHPUnit_Framework_Assert::assertCount($expectedErrorCount, $formErrorElts);
        }
    }

    /**
     * @Then /^I should see no validation errors in the "(?P<formName>[^"]*)" form$/
     */
    public function iShouldSeeNoValidationErrorsInTheForm($formName)
    {
        $formSelector = sprintf('form[name="%s"]', $formName);
        $this->assertSession()->elementExists('css', $formSelector);

        $formErrors = $this->getSession()->getPage()->findAll('css', sprintf('%s ul[id$="-errors"] li.error', $formSelector));

        \PHPUnit_Framework_Assert::assertCount(0, $formErrors);

        // Ensure that the form was submitted
        $this->assertSession()->elementExists('css', '.alert-info');
    }

    private function onTheCorrectSide()
    {
        if ($this->serverSide) {
            $this->assertSession()->elementExists('css', '.alert-info');
        } else {
            $this->assertSession()->elementNotExists('css', '.alert-info');
        }
    }

    /**
     * Returns Mink session assertion tool.
     *
     * @param string|null $name name of the session OR active session will be used
     *
     * @return WebAssert
     */
    public function assertSession($name = null)
    {
        return $this->getMink()->assertSession($name);
    }

    /**
     * @param string $fieldName
     * @param string $formName
     * @return string
     */
    public function resolveFieldSelector($fieldName, $formName)
    {
        $fieldName = trim($fieldName);
        if (!in_array($fieldName[0], array('[', '#', '.'), true)) {
            $fieldName = '[' . str_replace(' ', '][', $fieldName) . ']';
        }

        if ($formName === null) {
            return sprintf('*[name="%1$s%2$s"]', $formName, $fieldName);
        }
        return sprintf('form[name="%1$s"] *[name="%1$s%2$s"]', $formName, $fieldName);
    }

    /**
     * @Given /^I wait for jQuery to be active$/
     */
    public function iWaitForJQueryToBeActive()
    {
        $this->getSession()->wait(5000, '(0 === jQuery.active)');
    }
}
