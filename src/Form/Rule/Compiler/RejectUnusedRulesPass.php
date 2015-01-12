<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCompilerInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RejectUnusedRulesPass implements FormRuleCompilerInterface
{
    /**
     * @param FormRuleContextBuilder $context
     */
    public function compile(FormRuleContextBuilder $context)
    {
        $it = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($context->getGroups())
        );
        $groups = array_unique(array_filter(iterator_to_array($it, false)));

        /** @var RuleCollection $ruleCollection */
        foreach ($context->all() as $ruleCollection) {
            foreach ($ruleCollection as $name => $rule) {
                if (!$rule instanceof ConstraintRule) {
                    continue;
                }

                $rule->groups = array_intersect($rule->groups, $groups);

                if (empty($rule->groups)) {
                    $ruleCollection->remove($name);
                }
            }
        }
    }
}
