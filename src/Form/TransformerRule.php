<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;
use Symfony\Component\Validator\Constraint;

/**
 * A class for transformer rules.
 * These rules won't be removed since they are bound to the field transformer
 *
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class TransformerRule extends Rule
{
    public function __construct($name, $options = null, RuleMessage $message = null, array $depends = array())
    {
        parent::__construct($name, $options, $message, array(), $depends);
    }
}
