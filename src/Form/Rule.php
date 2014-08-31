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
     * @var RuleMessage
     */
    public $message;

    /**
     * @var array
     */
    public $groups = array(Constraint::DEFAULT_GROUP);

    public function __construct($name, $options = null, RuleMessage $message = null)
    {
        $this->name = $name;
        $this->options = $options;
        $this->message = $message;
    }
}
