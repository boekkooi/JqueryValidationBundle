<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormViewRecursiveIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class CollectionPrototypePass implements FormRuleProcessorInterface
{
    public function process(FormRuleProcessorContext $processContext, FormRuleContextBuilder $formRuleContext)
    {
        $form = $processContext->getForm();
        $view = $processContext->getView();

        // Check if this is a a prototype/collection type
        /** @var FormInterface|null $prototype */
        $prototype = $form->getConfig()->getAttribute('prototype');
        if (!$prototype || !isset($view->vars['prototype'])) {
            return;
        }

        /** @var FormView $prototypeView */
        $prototypeView = $view->vars['prototype'];

        // Extract the prototype rules from the default rules
        $prototypeContext = $this->extractRules(
            $formRuleContext,
            $prototype,
            $prototypeView
        );
        if (count($prototypeContext->all()) === 0) {
            return;
        }

        // Register builder
        $view->vars['rule_builder'] = $prototypeContext;
    }

    protected function extractRules(FormRuleContextBuilder $formRuleContext, FormInterface $form, FormView $view)
    {
        $extracted = new FormRuleContextBuilder();
        if ($form->getConfig()->getCompound()) {
            $it = new \RecursiveIteratorIterator(
                new FormViewRecursiveIterator($view->getIterator()),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            $found = array();
            foreach ($it as $childView) {
                $found[] = FormHelper::getFormName($childView);
            }
        } else {
            $found = array($view);
        }

        foreach ($found as $foundView) {
            $rules = $formRuleContext->get($foundView);
            if ($rules === null) {
                continue;
            }

            $extracted->add($foundView, $rules);
            $formRuleContext->remove($foundView);
        }

        return $extracted;
    }
}
