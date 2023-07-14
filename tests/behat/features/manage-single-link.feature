@javascript @retry
Feature: Manage Single Link
  As a cms author
  I want to manage link in the CMS

  Background:
    Given a "page" "About Us" has the "Content" "<p>My content</p>"
    Given a "page" "Contact us" has the "Content" "<p>Contact details</p>"
    And a "image" "assets/file2.jpg"
		And the "group" "AUTHOR" has permissions "Access to 'Pages' section"
    And the "group" "EDITOR" has permissions "Access to 'Pages' section" and "SITETREE_GRANT_ACCESS"
    And I am logged in as a member of "AUTHOR" group
    And I go to "/admin/pages"
    Then I should see "About Us" in the tree
    And I click on "About Us" in the tree
    And I should see an edit page form
    And I click the "Link test" CMS tab


  @javascript
  Scenario: I can fill an empty LinkField with a SiteTree link
    And I should see an empty "My test link" LinkField
    Then I edit the "My test link" LinkField
    And I should see an option to add a "Site Tree" link to the "My test link" LinkField
    And I should see an option to add a "External" link to the "My test link" LinkField
    And I should see an option to add a "File" link to the "My test link" LinkField
    And I should see an option to add a "Email" link to the "My test link" LinkField
    And I should see an option to add a "Phone" link to the "My test link" LinkField
    Then I add a "Site Tree" link to the "My test link" LinkField
    And I should see a "Site Tree" link modal
    Then I select "Contact us" in the "#Form_ModalsDynamicLink_PageID_Holder" tree dropdown
    And I fill in "Title" with "Test link site tree link"
    And I press the "Insert link" button
    And I should see a "My test link" LinkField filled with "Test link site tree link"
    Then I press the "Save" button
    And I should see a "My test link" LinkField filled with "Test link site tree link"


