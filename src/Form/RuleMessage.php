<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RuleMessage
{
    /**
     * A message text.
     * @var string
     */
    public $message;

    /**
     * A list of message parameters.
     * @var array
     */
    public $parameters;

    /**
     * @var null
     */
    public $plural;

    public function __construct($message, array $parameters = array(), $plural = null)
    {
        $this->message = $message;
        $this->parameters = $parameters;
        $this->plural = $plural;
    }
}
