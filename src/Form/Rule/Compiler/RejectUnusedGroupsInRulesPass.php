<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\FormContext;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RejectUnusedGroupsInRulesPass implements FormPassInterface {

    public function process(FormRuleCollection $collection, FormContext $context)
    {
        $rules = $collection->get($collection->getView());

        /** @var \Boekkooi\Bundle\JqueryValidationBundle\Form\Rule $rule */
        foreach ($rules as $name => $rule) {
            $rule->groups = array_intersect($rule->groups, $context->getGroups()->toArray());

            if (empty($rule->groups)) {
                $rules->remove($name);
            }
        }
    }
}