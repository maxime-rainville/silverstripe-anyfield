@javascript @retry
Feature: Manage Single item in Elemental block
  As a cms author
  I want to manage single item using the AnyField

  Background:
    Given I add an extension "DNADesign\Elemental\Extensions\ElementalPageExtension" to the "Page" class
    Given a "page" "About Us" has the "Content" "<p>My content</p>"
    Given a "page" "Contact me" has the "Content" "<p>Contact details</p>"
    And a "image" "assets/file2.jpg"
		And the "group" "EDITOR" has permissions "Access to 'Pages' section" and "SITETREE_GRANT_ACCESS" and "SITETREE_REORGANISE"
    And I am logged in as a member of "EDITOR" group
    And I go to "/admin/pages"
    Then I should see "About Us" in the tree
    And I click on "About Us" in the tree
    And I should see an edit page form
    And I press the "Add block" button
    And I press the "Link" button
    And I click on the ".element-editor__element" element

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
    And I fill in "Form_ModalsAnyFieldForm_Title" with "Test link site tree link"
    And I press the "Insert link" button
    And I should see a "My test link" AnyField filled with "Test link site tree link" and a description of "Site Tree Link: contact-me"
    Then I press the "Save" button
    And I click on the ".element-editor__element" element
    And I should see a "My test link" AnyField filled with "Test link site tree link" and a description of "Site Tree Link: contact-me"

  Scenario: I can clear a AnyField
    Then I edit the "My test link" AnyField
    And I add a "File Link" item to the "My test link" AnyField
    And I click on the ".gallery__files .gallery-item__thumbnail" element
    Then I fill in "Link description" with "A file link"
    Then I press the "Link to file" button
    And I press the "View actions" button
    And I click on the ".element-editor__actions-save" element
    Then I should see a "My test link" AnyField filled with "A file link" and a description of "File Link: file2.jpg"
    And I should see a clear button in the "My test link" AnyField
    Then I clear the "My test link" AnyField
    And I should see an empty "My test link" AnyField
    Then I press the "Save" button
    And I click on the ".element-editor__element" element
    And I should see an empty "My test link" AnyField

  Scenario: I can update an existing item
    Then I edit the "My test link" AnyField
    And I add a "Email Link" item to the "My test link" AnyField
    Then I fill in "Email" with "hello@example.com"
    And I press the "Insert link" button
    And I press the "View actions" button
    And I click on the ".element-editor__actions-save" element
    Then I should see a "My test link" AnyField filled with "hello@example.com" and a description of "Email Link: hello@example.com"
    Then I edit the "My test link" AnyField
    And I fill in "Form_ModalsAnyFieldForm_Title" with "My udated test link"
    And I press the "Insert link" button
    Then I should see a "My test link" AnyField filled with "My udated test link" and a description of "Email Link: hello@example.com"
    And I press the "View actions" button
    And I click on the ".element-editor__actions-save" element
    Then I should see a "My test link" AnyField filled with "My udated test link" and a description of "Email Link: hello@example.com"
    Then I press the "Save" button
