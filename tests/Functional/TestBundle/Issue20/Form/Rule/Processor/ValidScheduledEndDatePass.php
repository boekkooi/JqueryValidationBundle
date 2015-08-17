<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Condition\FieldValueDependency;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\RequiredRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormView;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Constraints\ValidScheduledEndDate;

class ValidScheduledEndDatePass implements FormRuleProcessorInterface
{

    /**
     * @param FormRuleProcessorContext $processContext
     * @param FormRuleContextBuilder $formRuleContext
     * @return void
     */
    public function process(FormRuleProcessorContext $processContext, FormRuleContextBuilder $formRuleContext)
    {
        $constraint = $this->getConstraint($processContext->getConstraints());
        if ($constraint === null) {
            return;
        }

        $formView = $processContext->getView();

        $requiredForm = $formView->children['isScheduledEndDate'];
        $endDateForm = $formView->children['scheduledEndDate'];

        $collection = new RuleCollection();
        $collection->set(
            RequiredRule::RULE_NAME,
            new ConstraintRule(
                RequiredRule::RULE_NAME,
                true,
                new RuleMessage($constraint->messageNotBlank),
                $constraint->groups,
                array(
                    new FieldValueDependency($requiredForm, FieldValueDependency::VALUE_EQUAL, '1')
                )
            )
        );

        $this->addRulesToForm($endDateForm, $collection, $formRuleContext);
    }

    private function addRulesToForm(FormView $form, RuleCollection $rules, FormRuleContextBuilder $ruleContext)
    {
        if (count($form->children) > 0) {
            foreach ($form->children as $child) {
                $this->addRulesToForm($child, $rules, $ruleContext);
            }
            return;
        }

        $ruleContext->add(
            $form,
            $rules
        );
    }

    /**
     * @param ConstraintCollection $constraints
     * @return ValidScheduledEndDate|null
     */
    private function getConstraint(ConstraintCollection $constraints)
    {
        foreach ($constraints as $constraint) {
            if (get_class($constraint) === 'Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Constraints\ValidScheduledEndDate') {
                return $constraint;
            }
        }

        return null;
    }
}
