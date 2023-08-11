@javascript @retry
Feature: Manage a list of items
  As a cms author
  I want to manage list of items using the ManyAnyField

  Background:
    Given a "page" "About Us" has the "Content" "<p>My content</p>"
    Given a "page" "Contact us" has the "Content" "<p>Contact details</p><a name='test-anchor'></a>"
    And a "image" "assets/file2.jpg"
		And the "group" "EDITOR" has permissions "Access to 'Pages' section" and "SITETREE_GRANT_ACCESS" and "SITETREE_REORGANISE"
    And I am logged in as a member of "EDITOR" group
    And I go to "/admin/pages"
    Then I should see "About Us" in the tree
    And I click on "About Us" in the tree
    And I should see an edit page form
    And I click the "Link test" CMS tab

  Scenario: I can add items to an empty ManyAnyField
    And I should see 0 item in "My test links" ManyAnyField
    Then I edit the "My test links" ManyAnyField
    And I should see an option to add a "Site Tree Link" item to the "My test links" ManyAnyField
    And I should see an option to add a "External Link" item to the "My test links" ManyAnyField
    And I should see an option to add a "File Link" item to the "My test links" ManyAnyField
    And I should see an option to add a "Email Link" item to the "My test links" ManyAnyField
    And I should see an option to add a "Phone Link" item to the "My test links" ManyAnyField
    Then I add a "Site Tree Link" item to the "My test links" ManyAnyField
    And I should see a "Site Tree Link" ManyAnyField modal
    Then I select "Contact us" in the "#Form_ModalsAnyFieldForm_PageID_Holder" tree dropdown
    And I fill in "Title" with "Test link site tree link"
    And I select "test-anchor" in the "#Form_ModalsAnyFieldForm_Anchor_Holder" anchor dropdown
    And I press the "Insert link" button
    And I should see a "My test links" ManyAnyField filled with "Test link site tree link" and a description of "Site Tree Link: contact-us" on position 1
    Then I press the "Save" button
    And I should see a "My test links" ManyAnyField filled with "Test link site tree link" and a description of "Site Tree Link: contact-us" on position 1

  Scenario: I can clear a ManyAnyField
    Then I edit the "My test links" ManyAnyField
    And I add a "Email Link" item to the "My test links" ManyAnyField
    Then I fill in "Email" with "hello@example.com"
    Then I press the "Insert link" button

    Then I edit the "My test links" ManyAnyField
    And I add a "File Link" item to the "My test links" ManyAnyField
    And I click on the ".gallery__files .gallery-item__thumbnail" element
    Then I fill in "Link description" with "A file link"
    Then I press the "Link to file" button

    Then I should see 2 items in "My test links" ManyAnyField
    And I should see a "My test links" ManyAnyField filled with "hello@example.com" and a description of "Email Link: hello@example.com" on position 1
    And I should see a "My test links" ManyAnyField filled with "A file link" and a description of "File Link: file2.jpg" on position 2

    Then I clear item 1 from the "My test links" ManyAnyField
    And I should see 1 item in "My test links" ManyAnyField
    And I should see a "My test links" ManyAnyField filled with "A file link" and a description of "File Link: file2.jpg" on position 1

    Then I press the "Save" button
    Then I clear item 1 from the "My test links" ManyAnyField
    And I should see 0 item in "My test links" ManyAnyField

    Then I press the "Save" button
    And I should see 0 item in "My test links" ManyAnyField

  Scenario: I can update an existing item in a ManyAnyField
    Then I edit the "My test links" ManyAnyField
    And I add a "External Link" item to the "My test links" ManyAnyField
    Then I fill in "Title" with "Silverstripe CMS"
    And I fill in "External url" with "https://www.silverstripe.org"
    Then I press the "Insert link" button

    Then I edit the "My test links" ManyAnyField
    And I add a "Phone Link" item to the "My test links" ManyAnyField
    And I fill in "Title" with "NZ Emergency services"
    Then I fill in "Phone" with "111"
    Then I press the "Insert link" button

    Then I should see 2 items in "My test links" ManyAnyField
    And I should see a "My test links" ManyAnyField filled with "Silverstripe CMS" and a description of "External Link: https://www.silverstripe.org" on position 1
    And I should see a "My test links" ManyAnyField filled with "NZ Emergency services" and a description of "Phone Link: 111" on position 2

    Then I edit item 1 from the "My test links" ManyAnyField
    And I should see a "External Link" ManyAnyField modal
    And the "Form_ModalsAnyFieldForm_Title" field should contain "Silverstripe CMS"
    And the "External url" field should contain "https://www.silverstripe.org"
    Then I fill in "Title" with "Silverstripe"
    And I fill in "External url" with "https://www.silverstripe.com"
    Then I press the "Insert link" button

    Then I should see 2 items in "My test links" ManyAnyField
    And I should see a "My test links" ManyAnyField filled with "Silverstripe" and a description of "External Link: https://www.silverstripe.com" on position 1

    Then I press the "Save" button

    Then I edit item 2 from the "My test links" ManyAnyField
    And I should see a "Phone Link" ManyAnyField modal
    And the "Form_ModalsAnyFieldForm_Title" field should contain "NZ Emergency services"
    And the "Phone" field should contain "111"
    And I fill in "Title" with "Canada Emergency services"
    Then I fill in "Phone" with "911"

    Then I click on the "button.close" element
    And I should see 2 items in "My test links" ManyAnyField
    And I should see a "My test links" ManyAnyField filled with "NZ Emergency services" and a description of "Phone Link: 111" on position 2
