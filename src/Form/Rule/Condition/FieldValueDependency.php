<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Condition;

use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCondition;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;

/**
 * Class used to indicate that a rule will only be validated when a field has a specific value.
 */
class FieldValueDependency implements RuleCondition
{
    const VALUE_EQUAL = '=';
    const VALUE_NOT_EQUAL = '!=';

    /**
     * Dependent field
     * @var string
     */
    public $field;

    /**
     * @var string
     */
    public $condition;

    /**
     * @var mixed
     */
    public $value;

    public function __construct($field, $condition = self::VALUE_EQUAL, $value)
    {
        $this->field = FormHelper::getFormName($field);
        $this->condition = $condition;
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function macro()
    {
        return 'field_value_dependency';
    }
}
