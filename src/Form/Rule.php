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
     * A list of field names that require to be valid before the rule is used
     * @var array
     */
    public $depends;

    public function __construct($name, $options = null, RuleMessage $message = null, array $depends = array())
    {
        $this->name = $name;
        $this->options = $options;
        $this->message = $message;
        $this->depends = $depends;
    }
}
