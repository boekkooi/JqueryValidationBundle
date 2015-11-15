Supported Constraints and DataTransformers
=============

Here is  a list of supported constraints and data transformers.

Constraints
-------------
Constraints are part of the Symfony validator component.
Consult the [Symfony Validation Constraints Reference](http://symfony.com/doc/current/reference/constraints.html) for
possible constraints.

#### Basic Constraints
| Constraint         | Basic       | Additionals | Extra Information |
| ------------------ | ----------- | ----------- | ----------------- |
| NotBlank           | [Yes]((../src/Form/Rule/Mapping/RequiredRule.php)) | Yes | |
| Blank              | No | No | |
| NotNull            | [Yes](../src/Form/Rule/Mapping/RequiredRule.php) | Yes | |
| Null               | No | No | |
| IsTrue             | Partial ([checkbox](../src/Form/Rule/Mapping/IsBooleanRule.php)) | [Yes](../src/Form/Rule/Mapping/IsBooleanRule.php) | |
| IsFalse            | No | [Yes](../src/Form/Rule/Mapping/IsBooleanRule.php) | |
| Type               | Partial | Partial | Types ['int','integer','float','double'](../src/Form/Rule/Mapping/NumberRule.php) are supported |

####  String Constraints
| Constraint         | Basic       | Additionals | Extra Information |
| ------------------ | ----------- | ----------- | ----------------- |
| Email              | [Yes](../src/Form/Rule/Mapping/EmailRule.php) | Yes | |
| Length             | Yes ([min](../src/Form/Rule/Mapping/MinLengthRule.php) [max](../src/Form/Rule/Mapping/MaxLengthRule.php)) | Yes | |
| Url                | Partial | No | Protocols ['http', 'https', 'ftp'](../src/Form/Rule/Mapping/UrlRule.php) |
| Regex              | No | [Yes](../src/Form/Rule/Mapping/PatternRule.php) | |
| Ip                 | No | [Yes](../src/Form/Rule/Mapping/IpRule.php) | |
| Uuid               | No | No | |

#### Number Constraints
| Constraint         | Basic       | Additionals | Extra Information |
| ------------------ | ----------- | ----------- | ----------------- |
| Range              | Yes ([min](../src/Form/Rule/Mapping/MinRule.php) [max](../src/Form/Rule/Mapping/MaxRule.php) [type](../src/Form/Rule/Mapping/NumberRule.php)) | Yes | |

#### Comparison Constraints
| Constraint         | Basic       | Additionals | Extra Information |
| ------------------ | ----------- | ----------- | ----------------- |
| EqualTo            | No | [Partial](../src/Form/Rule/Mapping/ValueRuleMapping.php) | Only supports scalar values |
| NotEqualTo         | No | [Partial](../src/Form/Rule/Mapping/ValueRuleMapping.php) | Only supports scalar values |
| IdenticalTo        | No | [Partial](../src/Form/Rule/Mapping/ValueRuleMapping.php) | Only supports scalar values |
| NotIdenticalTo     | No | [Partial](../src/Form/Rule/Mapping/ValueRuleMapping.php) | Only supports scalar values |
| LessThan           | [Partial](../src/Form/Rule/Mapping/MaxRule.php) | Partial | Floats are currently not fully supported |
| LessThanOrEqual    | [Yes](../src/Form/Rule/Mapping/MaxRule.php)     | Yes     | |
| GreaterThan        | [Partial](../src/Form/Rule/Mapping/MinRule.php) | Partial | Floats are currently not fully supported |
| GreaterThanOrEqual | [Yes](../src/Form/Rule/Mapping/MinRule.php)     | Yes     | |

#### Date Constraints
| Constraint         | Basic       | Additionals | Extra Information |
| ------------------ | ----------- | ----------- | ----------------- |
| Date               | No | No | Date DataTransformer is supported      |
| DateTime           | No | No | DateTime DataTransformer is supported  |
| Time               | No | No | Time DataTransformer is supported      |

#### Collection Constraints
| Constraint         | Basic       | Additionals | Extra Information |
| ------------------ | ----------- | ----------- | ----------------- |
| Choice             | Partial | Partial | ([min](../src/Form/Rule/Mapping/MinLengthRule.php) [max](../src/Form/Rule/Mapping/MaxLengthRule.php) choices) | | 
| Collection         | No | No | |
| Count              | No | No | |
| UniqueEntity       | No | No | For security reasons this will not be implemented |
| Language           | No | No | |
| Locale             | No | No | |
| Country            | No | No | |

#### File Constraints
| Constraint         | Basic       | Additionals | Extra Information |
| ------------------ | ----------- | ----------- | ----------------- |
| File               | No | [Yes](../src/Form/Rule/Mapping/FileRule.php) | |
| Image              | No | No | |

#### Financial and other Number Constraints
| Constraint         | Basic       | Additionals | Extra Information |
| ------------------ | ----------- | ----------- | ----------------- |
| CardScheme         | [Yes](../src/Form/Rule/Mapping/CreditcardRule.php) | Yes | |
| Currency           | No | No | |
| Luhn               | No | [Yes](../src/Form/Rule/Mapping/LuhnRule.php) | |
| Iban               | No | [Yes](../src/Form/Rule/Mapping/IbanRule.php) | |
| Isbn               | No | No | |
| Issn               | No | No | |

#### Other Constraints
| Constraint         | Basic       | Additionals | Extra Information |
| ------------------ | ----------- | ----------- | ----------------- |
| Callback           | No | No | This can never be supported  |
| Expression         | No | No | Almost impossible to support |
| All                | No | No | |
| UserPassword       | No | No | For security reasons this will not be implemented |
| Valid              | Yes | Yes | There is no direct rule mapper for this see [FormDataConstraintFinder](../src/Form/FormDataConstraintFinder.php) and [ValidConstraintPass](../src/Form/Rule/Processor/ValidConstraintPass.php) | 

DataTransformer
-------------
DataTransformers are part of the Symfony form component and are run before the validator/constraints.

| DataTransformer                       | Basic       | Additionals | Extra Information | 
| ------------------------------------- | ----------- | ----------- | ----------------- |
| ArrayToPartsTransformer               | No | No | |
| BooleanToStringTransformer            | No | No | |
| ChoicesToBooleanArrayTransformer      | No | No | |
| ChoicesToValuesTransformer            | No | No | |
| ChoiceToBooleanArrayTransformer       | No | No | |
| ChoiceToValueTransformer              | No | No | |
| DataTransformerChain                  | No | No | |
| DateTimeToArrayTransformer            | [Yes](../src/Form/Rule/Processor/DateTimeToArrayTransformerPass.php)      | Yes | |
| DateTimeToLocalizedStringTransformer  | No | No | |
| DateTimeToRfc3339Transformer          | [Partial](../src/Form/Rule/Processor/DateTimeToStringTransformerPass.php) | Partial | Only for form types TimeType, DateType, DateTimeType |
| DateTimeToStringTransformer           | [Partial](../src/Form/Rule/Processor/DateTimeToStringTransformerPass.php) | Partial | Only for form types TimeType, DateType, DateTimeType |
| DateTimeToTimestampTransformer        | No | No | |
| IntegerToLocalizedStringTransformer   | No | No | |
| MoneyToLocalizedStringTransformer     | No | No | |
| NumberToLocalizedStringTransformer    | No | No | |
| PercentToLocalizedStringTransformer   | No | No | |
| ValueToDuplicatesTransformer          | [Partial](../src/Form/Rule/Processor/ValueToDuplicatesTransformer.php)    | Partial | Only for none compund fields |
