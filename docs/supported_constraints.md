Supported Constraints
-------------

Consult the [Symfony Validation Constraints Reference](http://symfony.com/doc/current/reference/constraints.html) for
possible constraints.

#### Basic Constraints

* NotBlank
* Blank (not yet supported)
* NotNull
* IsNull (not yet supported)
* IsTrue
* IsFalse
* Type (not yet supported)

#### String Constraints

* Email
* Length
* Url
* Regex
* Ip
* Uuid (not yet supported)

#### Number Constraints

* Range

#### Comparison Constraints

* EqualTo
* NotEqualTo (not yet supported)
* IdenticalTo (not yet supported)
* NotIdenticalTo (not yet supported)
* LessThan
* LessThanOrEqual
* GreaterThan
* GreaterThanOrEqual

#### Date Constraints

* Date
* DateTime (not yet supported)
* Time (not yet supported)

#### Collection Constraints

* Choice (not yet supported)
* Collection (unknown support status)
* Count (not yet supported)
* UniqueEntity (won't be supported due to security concerns - create by your own with ajax and controller call)
* Language (not yet supported)
* Locale (not yet supported)
* Country (not yet supported)

#### File Constraints

* File (not yet supported)
* Image (not yet supported)

#### Financial and other Number Constraints

* CardScheme (schemes not supported)
* Currency (not yet supported)
* Luhn
* Iban
* Isbn (not yet supported)
* Issn (not yet supported)

#### Other Constraints

* Callback (not yet supported)
* Expression (not yet supported)
* All (not yet supported)
* UserPassword (not yet supported)
* Valid (not yet supported)
