<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class Rule
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
     * A list of validation groups
     * @var array
     */
    public $groups;

    /**
     * A list of field names that require to be valid before the rule is used
     * @var array
     */
    public $depends;

    public function __construct($name, $options = null, RuleMessage $message = null, array $groups = array(Constraint::DEFAULT_GROUP), array $depends = array())
    {
        $this->name = $name;
        $this->options = $options;
        $this->message = $message;
        $this->groups = $groups;
        $this->depends = $depends;
    }
}
