<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormViewRecursiveIterator;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class CompoundCopyToChildPass implements FormRuleProcessorInterface
{
    protected static $copyForTypes = array(
        'Symfony\\Component\\Form\\Extension\\Core\\Type\\DateTimeType',
        'Symfony\\Component\\Form\\Extension\\Core\\Type\\TimeType',
        'Symfony\\Component\\Form\\Extension\\Core\\Type\\DateType'
    );

    public function process(FormRuleProcessorContext $context, FormRuleContextBuilder $formRuleContext)
    {
        $form = $context->getForm();
        $formConfig = $form->getConfig();
        if (!$formConfig->getCompound() || $context->getConstraints()->count() === 0) {
            return;
        }

        $formView = $context->getView();
        $rules = $formRuleContext->get($formView);
        if ($rules === null || $rules->count() === 0 || !$this->requiresCopy($form)) {
            return;
        }

        $this->registerRulesForChildren($formRuleContext, $formView, $this->getFormRuleMessage($formConfig));
    }

    protected function requiresCopy(FormInterface $form)
    {
        $type = get_class($form->getConfig()->getType()->getInnerType());

        return in_array($type, static::$copyForTypes, true);
    }

    private function registerRulesForChildren(FormRuleContextBuilder $formRuleContext, FormView $view, RuleMessage $message)
    {
        // Copy parent rules to the children
        $rules = $formRuleContext->get($view);

        /** @var FormView[]|\RecursiveIteratorIterator $it */
        $it = new \RecursiveIteratorIterator(
            new FormViewRecursiveIterator($view->getIterator()),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        /* @var \Boekkooi\Bundle\JqueryValidationBundle\Form\Rule[] $rules */
        foreach ($rules as $name => $baseRule) {
            // Prepare a new rule
            $rule = clone $baseRule;
            $rule->message = $message;

            $it->rewind();
            foreach ($it as $childView) {
                $collection = new RuleCollection();
                $collection->set($name, $rule);

                $formRuleContext->add($childView, $collection);

                $rule = clone $rule;
                $rule->depends[] = $childView->vars['full_name'];
            }
        }

        // Clear rules since it's a compound field
        $formRuleContext->remove($view);
    }

    protected function getFormRuleMessage(FormConfigInterface $config)
    {
        // Get correct error message if one is set.
        if ($config->hasOption('invalid_message')) {
            // TODO support invalid_message_parameters
            return new RuleMessage($config->getOption('invalid_message'));
        }

        return null;
    }
}
