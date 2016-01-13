<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormViewRecursiveIterator;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ValidConstraintPass implements FormRuleProcessorInterface
{
    public function process(FormRuleProcessorContext $processContext, FormRuleContextBuilder $formRuleContext)
    {
        $form = $processContext->getForm();
        if (!$this->requiresValidConstraint($form) || $this->hasValidConstraint($processContext->getConstraints())) {
            return;
        }

        $view = $processContext->getView();
        $it = new \RecursiveIteratorIterator(
            new FormViewRecursiveIterator($view->getIterator()),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($it as $childView) {
            if (isset($childView->vars['required'])) {
                $childView->vars['required'] = false;
            }

            $this->cleanChildRules($childView, $formRuleContext);
        }
    }

    private function requiresValidConstraint(FormInterface $form)
    {
        $formConfig = $form->getConfig();

        return !$form->isRoot() &&
            $formConfig->getCompound() &&
            $formConfig->getMapped() &&
            $formConfig->getDataClass() !== null
        ;
    }

    private function hasValidConstraint(ConstraintCollection $constraints)
    {
        foreach ($constraints as $constraint) {
            if (get_class($constraint) === Valid::class) {
                return true;
            }
        }

        return false;
    }

    private function cleanChildRules(FormView $childView, FormRuleContextBuilder $formRuleContext)
    {
        $rules = $formRuleContext->get($childView);
        if ($rules === null) {
            return;
        }

        // Don't remove transformer rules!
        foreach ($rules as $name => $rule) {
            if (!$rule instanceof ConstraintRule) {
                continue;
            }
            $rules->remove($name);
        }

        if (empty($rules)) {
            $formRuleContext->remove($childView);
        }
    }
}
