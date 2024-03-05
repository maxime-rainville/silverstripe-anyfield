@javascript @retry
Feature: Manage Single item
  As a cms author
  I want to manage single item using the AnyField

  Background:
    Given a "page" "About Us" has the "Content" "<p>My content</p>"
    Given a "page" "Contact me" has the "Content" "<p>Contact details</p>"
    And a "image" "assets/file2.jpg"
		And the "group" "EDITOR" has permissions "Access to 'Pages' section" and "SITETREE_GRANT_ACCESS" and "SITETREE_REORGANISE"
    And I am logged in as a member of "EDITOR" group
    And I go to "/admin/pages"
    Then I should see "About Us" in the tree
    And I click on "About Us" in the tree
    And I should see an edit page form
    And I click the "Link test" CMS tab

  Scenario: I can fill an empty AnyField with a link
    And I should see an empty "My test link" AnyField
    Then I edit the "My test link" AnyField
    And I should see an option to add a "Site Tree Link" item to the "My test link" AnyField
    And I should see an option to add a "External Link" item to the "My test link" AnyField
    And I should see an option to add a "File Link" item to the "My test link" AnyField
    And I should see an option to add a "Email Link" item to the "My test link" AnyField
    And I should see an option to add a "Phone Link" item to the "My test link" AnyField
    Then I add a "Site Tree Link" item to the "My test link" AnyField
    And I should see a "Site Tree Link" AnyField modal
    Then I select "Contact me" in the "#Form_ModalsAnyFieldForm_PageID_Holder" tree dropdown
    And I fill in "Link text" with "Test link site tree link"
    And I press the "Insert link" button
    And I should see a "My test link" AnyField filled with "Test link site tree link" and a description of "Site Tree Link: contact-me"
    Then I press the "Save" button
    And I should see a "My test link" AnyField filled with "Test link site tree link" and a description of "Site Tree Link: contact-me"

  Scenario: I can clear a AnyField
    Then I edit the "My test link" AnyField
    And I add a "Site Tree Link" item to the "My test link" AnyField
    And I select "Contact me" in the "#Form_ModalsAnyFieldForm_PageID_Holder" tree dropdown
    And I fill in "Link text" with "Test link site tree link"
    And I press the "Insert link" button
    And I press the "Save" button
    Then I should see a "My test link" AnyField filled with "Test link site tree link" and a description of "Site Tree Link: contact-me"
    And I should see a clear button in the "My test link" AnyField
    Then I clear the "My test link" AnyField
    And I wait for 2 seconds
    And I should see an empty "My test link" AnyField
    Then I press the "Save" button
    And I should see an empty "My test link" AnyField

  Scenario: I can update an existing item
    Then I edit the "My test link" AnyField
    And I add a "Site Tree Link" item to the "My test link" AnyField
    And I select "Contact me" in the "#Form_ModalsAnyFieldForm_PageID_Holder" tree dropdown
    And I fill in "Link text" with "Test link site tree link"
    And I press the "Insert link" button
    And I press the "Save" button
    Then I should see a "My test link" AnyField filled with "Test link site tree link" and a description of "Site Tree Link: contact-me"
    Then I edit the "My test link" AnyField
    And I fill in "Link text" with "My udated test link"
    And I press the "Insert link" button
    Then I should see a "My test link" AnyField filled with "My udated test link" and a description of "Site Tree Link: contact-me"
    Then I press the "Save" button
    Then I should see a "My test link" AnyField filled with "My udated test link" and a description of "Site Tree Link: contact-me"

  Scenario: I can fill a AnyField with an external item
    Then I edit the "My test link" AnyField
    And I add a "External Link" item to the "My test link" AnyField
    Then I fill in "Link text" with "Silverstripe"
    And I fill in "External url" with "https://www.silverstripe.org"
    Then I press the "Insert link" button
    And I press the "Save" button
    Then I should see a "My test link" AnyField filled with "Silverstripe" and a description of "External Link: https://www.silverstripe.org"

  Scenario: I can fill a AnyField with an email link
    Then I edit the "My test link" AnyField
    And I add a "Email Link" item to the "My test link" AnyField
    Then I fill in "Email" with "hello@example.com"
    Then I press the "Insert link" button
    And I press the "Save" button
    Then I should see a "My test link" AnyField filled with "hello@example.com" and a description of "Email Link: hello@example.com"

  Scenario: I can fill a AnyField with a file link
    Then I edit the "My test link" AnyField
    And I add a "File Link" item to the "My test link" AnyField
    Then I fill in "Link text" with "A file link"
    And I press the "Choose existing" button
    And I click on the ".insert-media-modal .toolbar__back-button" element
    And I click on the ".gallery__files .gallery-item__thumbnail" element
    Then I press the "Insert" button
    Then I press the "Insert link" button
    And I press the "Save" button
    Then I should see a "My test link" AnyField filled with "A file link" and a description of "File Link: file2.jpg"

  Scenario: I can fill a AnyField with a phone link
    Then I edit the "My test link" AnyField
    And I add a "Phone Link" item to the "My test link" AnyField
    Then I fill in "Phone" with "111"
    And I fill in "Link text" with "NZ Emergency services"
    Then I press the "Insert link" button
    And I press the "Save" button
    Then I should see a "My test link" AnyField filled with "NZ Emergency services" and a description of "Phone Link: 111"
