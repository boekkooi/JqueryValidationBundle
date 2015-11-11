Feature: Testing validation groups based on the button that is pressed

  Background:
    Given I go to "/buttons"
      And I wait for jQuery to be active

  Scenario Outline: I can submit a valid form instance
    When I fill in the "buttons" form with the following:
      | title   | John Doe     |
      | content | -            |
    And I submit "buttons" by clicking on "<button>"
    Then I should see no validation errors in the "buttons" form

    Examples:
      | button                   |
      | defaultValidation        |
      | mainValidation           |
      | mainAndDefaultValidation |
      | noValidation             |

  Scenario: I can always submit a form when validation groups is false
    When I fill in the "buttons" form with the following:
      | title   | |
      | content | |
    And I submit "buttons" by clicking on "noValidation"
    Then I should see no validation errors in the "buttons" form

  # NotBlank => [ 'groups' => [ 'main', 'Default' ] ]
  Scenario Outline: I can have constraints that have multiple validation groups
    When I fill in the "buttons" form with the following:
      | title   |              |
      | content | some content |
    And I submit "buttons" by clicking on "<button>"
    Then I should only see the following validation errors in the "buttons" form:
      | title | This value should not be blank. |

    Examples:
      | button                   |
      | defaultValidation        |
      | mainValidation           |
      | mainAndDefaultValidation |

  # Length => [ 'groups' => 'main' ]
  Scenario Outline: I can have a constraint that is only used by a single group (main)
    When I fill in the "buttons" form with the following:
      | title   | a            |
      | content | some content |
    And I submit "buttons" by clicking on "<button>"
    Then I should only see the following validation errors in the "buttons" form:
      | title | This value is too short. It should have 8 characters or more. |

    Examples:
      | button                   |
      | mainValidation           |
      | mainAndDefaultValidation |

  Scenario: I can submit when a constraint is invalid but not I my group (main)
    When I fill in the "buttons" form with the following:
      | title   | a            |
      | content | some content |
    And I submit "buttons" by clicking on "defaultValidation"
    Then I should see no validation errors in the "buttons" form

  # Length => [ 'groups' => 'Default' ]
  Scenario Outline: I can have a constraint that is only used by a single group (main)
    When I fill in the "buttons" form with the following:
      | title   | John Doe |
      | content |          |
    And I submit "buttons" by clicking on "<button>"
    Then I should only see the following validation errors in the "buttons" form:
      | content | This value should not be blank. |

    Examples:
      | button                   |
      | defaultValidation        |
      | mainAndDefaultValidation |

  Scenario: I can submit when a constraint is invalid but not I my group (main)
    When I fill in the "buttons" form with the following:
      | title   | John Doe |
      | content |          |
    And I submit "buttons" by clicking on "mainValidation"
    Then I should see no validation errors in the "buttons" form
