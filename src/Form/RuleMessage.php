<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RuleMessage
{
    public $message;

    public $parameters;

    public $plural;

    public function __construct($message, array $parameters = array(), $plural = null)
    {
        $this->message = $message;
        $this->parameters = $parameters;
        $this->plural = $plural;
    }
}
