<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ConstraintGroupFilterPass implements FormPassInterface
{
    public function process(FormRuleCollection $collection, $constraints)
    {
        // TODO get the current group, remove any constraint that is not valid for the groups.
        // unset();
    }
}