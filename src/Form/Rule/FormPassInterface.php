<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Symfony\Component\Form\FormInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
interface FormPassInterface
{
    public function process(FormRuleCollection $collection, ConstraintCollection $constraints);
}
