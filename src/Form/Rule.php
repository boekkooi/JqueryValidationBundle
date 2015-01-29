<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

/**
 * Abstract base rule.
 *
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
abstract class Rule
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var mixed
     */
    public $options;

    /**
     * @var RuleMessage|null
     */
    public $message;

    /**
     * A list rule conditions.
     * @var RuleCondition[]
     */
    public $conditions;

    public function __construct($name, $options = null, RuleMessage $message = null, array $conditions = array())
    {
        $this->name = $name;
        $this->options = $options;
        $this->message = $message;
        $this->conditions = $conditions;
    }
}
