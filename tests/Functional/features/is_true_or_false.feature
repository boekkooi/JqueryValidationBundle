@constraintIsTrueExists
Feature: Testing validation using IsTrue or IsFalse constraints

  Background:
    Given I go to "/is_true_or_false"
      And I wait for jQuery to be active

  Scenario: I can submit a valid form instance
    When I fill in the "is_true_or_false_form" form with the following:
      | check-true       | 1 |
      | select-bool-true | 1 |
      | select-int-true  | 1 |
      | text-true        | 1 |
      | check-false      | 0 |
      | select-false     | 0 |
      | text-false       | 0 |
    And I submit "is_true_or_false_form"
    Then I should see no validation errors in the "is_true_or_false_form" form

  @clientSide @basic
  Scenario: I see only checkbox isTrue errors when additionals are not enabled
    When I fill in the "is_true_or_false_form" form with the following:
      | check-true       | 0     |
      | select-bool-true | 0     |
      | select-int-true  | 0     |
      | text-true        | true  |
      | check-false      | 1     |
      | select-false     | 1     |
      | text-false       | false |
    And I submit "is_true_or_false_form"
    Then I should only see the following validation errors in the "is_true_or_false_form" form:
      | check-true       | This value should be true.  |

  @additionals @server-side
  Scenario: I see errors when the value is not what is expected additional
    When I fill in the "is_true_or_false_form" form with the following:
      | check-true       | 0     |
      | select-bool-true | 0     |
      | select-int-true  | 0     |
      | text-true        | true  |
      | check-false      | 1     |
      | select-false     | 1     |
      | text-false       | false |
    And I submit "is_true_or_false_form"
    Then I should only see the following validation errors in the "is_true_or_false_form" form:
      | check-true       | This value should be true.  |
      | select-bool-true | This value should be true.  |
      | select-int-true  | This value should be true.  |
      | text-true        | This value should be true.  |
      | check-false      | This value should be false. |
      | select-false     | This value should be false. |
      | text-false       | This value should be false. |
