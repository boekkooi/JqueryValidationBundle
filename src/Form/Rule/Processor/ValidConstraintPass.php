<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\TransformerRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormViewRecursiveIterator;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ValidConstraintPass implements FormRuleProcessorInterface
{
    const VALID_CONSTRAINT_CLASS = 'Symfony\Component\Validator\Constraints\Valid';

    public function process(FormRuleProcessorContext $processContext, FormRuleContextBuilder $formRuleContext)
    {
        $form = $processContext->getForm();
        $view = $processContext->getView();

        $formConfig = $form->getConfig();
        if ($form->isRoot() || !$formConfig->getCompound() || $formConfig->getDataClass() === null || !$formConfig->getMapped()) {
            return;
        }

        $constraints = $processContext->getConstraints();
        foreach ($constraints as $constraint) {
            if (get_class($constraint) === self::VALID_CONSTRAINT_CLASS) {
                return;
            }
        }

        $it = new \RecursiveIteratorIterator(
            new FormViewRecursiveIterator($view->getIterator()),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($it as $childView) {
            if (isset($childView->vars['required'])) {
                $childView->vars['required'] = false;
            }

            $rules = $formRuleContext->get($childView);
            if ($rules === null) {
                continue;
            }

            // Don't remove transformer rules!
            foreach($rules as $name => $rule) {
                if ($rule instanceof TransformerRule) {
                    continue;
                }
                $rules->remove($name);
            }

            if (empty($rules)) {
                $formRuleContext->remove($childView);
            }
        }
    }
}
