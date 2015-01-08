<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ConstraintRule extends Rule
{
    /**
     * A list of validation groups
     * @var array
     */
    public $groups;

    public function __construct($name, $options = null, RuleMessage $message = null, array $groups = array(Constraint::DEFAULT_GROUP), array $depends = array())
    {
        parent::__construct($name, $options, $message, $depends);

        $this->groups = $groups;
    }
}
