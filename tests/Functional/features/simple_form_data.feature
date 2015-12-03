Feature: Testing a simple form with constraint fetched form a data object

  Background:
    Given I go to "/simple_data"
      And I wait for jQuery to be active

  Scenario: I can submit a valid form instance
    When I fill in the "simple_data_form" form with the following:
      | name            | John Doe            |
      | password first  | aVerySecretPassword |
      | password second | aVerySecretPassword |
    And I submit "simple_data_form"
    Then I should see no validation errors in the "simple_data_form" form

  Scenario: Both name and password are required
    When I submit "simple_data_form"
    Then I should only see the following validation errors in the "simple_data_form" form:
      | name           | This value should not be blank. |
      | password first | This value should not be blank. |

  Scenario: Name and password must have atleast 2 chars
    When I fill in the "simple_data_form" form with the following:
      | name            | a |
      | password first  | a |
      | password second | a |
    And I submit "simple_data_form"
    Then I should only see the following validation errors in the "simple_data_form" form:
      | name           | This value is too short. It should have 2 characters or more. |
      | password first | This value is too short. It should have 2 characters or more. |

  Scenario: Name and password may not be longer then 255 chars
    When I fill in the "simple_data_form" form field with a string of length 256 the following:
      | name            |
      | password first  |
      | password second |
    And I submit "simple_data_form"
    Then I should only see the following validation errors in the "simple_data_form" form:
      | name           | This value is too long. It should have 255 characters or less. |
      | password first | This value is too long. It should have 255 characters or less. |

  # There is a differences between jquery validation and Symfony

  # # Validation order
  # In Jquery validate we make no difference between transformers and constraints
  # This means that rules added by a transformer that have dependencies are applied
  # at once a fields constraint is applied.
  # This is different from Symfony because symfony will first apply a transformer and then the constraints.
  @clientSide
  Scenario: Name and password must have atleast 2 chars
    When I fill in the "simple_data_form" form with the following:
      | name            | John |
      | password first  | a    |
      | password second |      |
    And I submit "simple_data_form"
    Then I should only see the following validation errors in the "simple_data_form" form:
      | password first | This value is too short. It should have 2 characters or more. |

  @serverSide
  Scenario: Name and password must have atleast 2 chars
    When I fill in the "simple_data_form" form with the following:
      | name            | John |
      | password first  | a    |
      | password second |      |
    And I submit "simple_data_form"
    Then I should only see the following validation errors in the "simple_data_form" form:
      | password first  | This value is not valid. |

  # # EqualTo
  # The equalTo rule is applied on the dependent field in Jquery
  # but it is applied on the compound/first field by Symfony
  @clientSide
  Scenario: Password must match
    When I fill in the "simple_data_form" form with the following:
      | name            | John Doe            |
      | password first  | aVerySecretPassword |
      | password second |                     |
    And I submit "simple_data_form"
    Then I should only see the following validation errors in the "simple_data_form" form:
      | password second  | This value is not valid. |

  @serverSide
  Scenario: Password must match
    When I fill in the "simple_data_form" form with the following:
      | name            | John Doe            |
      | password first  | aVerySecretPassword |
      | password second |                     |
    And I submit "simple_data_form"
    Then I should only see the following validation errors in the "simple_data_form" form:
      | password first  | This value is not valid. |
