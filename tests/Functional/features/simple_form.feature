Feature: Testing a simple form

  Background:
    Given I go to "/"
      And I wait for jQuery to be active

  Scenario: I can submit a valid form instance
    When I fill in the "simple_form" form with the following:
      | name            | John Doe            |
      | password first  | aVerySecretPassword |
      | password second | aVerySecretPassword |
    And I submit "simple_form"
    Then I should see no validation errors in the "simple_form" form

  Scenario: Both name and password are required
    When I submit "simple_form"
    Then I should only see the following validation errors in the "simple_form" form:
      | name           | This value should not be blank. |
      | password first | This value should not be blank. |

  Scenario: Name must have atleast 2 chars
    When I fill in the "simple_form" form with the following:
      | name            | a                   |
      | password first  | aVerySecretPassword |
      | password second | aVerySecretPassword |
      And I submit "simple_form"
    Then I should only see the following validation errors in the "simple_form" form:
      | name  | This value is too short. It should have 2 characters or more. |

  # There is a common difference between jquery validation and Symfony
  # This is that the equalTo rule is applied on the dependent field in Jquery
  # but it is applied on the compound/first field by Symfony
  @clientSide
  Scenario: Password must match
    When I fill in the "simple_form" form with the following:
      | name            | John Doe            |
      | password first  | aVerySecretPassword |
      | password second |                     |
    And I submit "simple_form"
    Then I should only see the following validation errors in the "simple_form" form:
      | password second  | WRONG! |

  @serverSide
  Scenario: Password must match
    When I fill in the "simple_form" form with the following:
      | name            | John Doe            |
      | password first  | aVerySecretPassword |
      | password second |                     |
    And I submit "simple_form"
    Then I should only see the following validation errors in the "simple_form" form:
      | password first  | WRONG! |
