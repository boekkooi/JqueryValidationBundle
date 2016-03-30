<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Condition\FieldDependency;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\RequiredRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\TransformerRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormViewRecursiveIterator;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class CompoundCopyToChildPass implements FormRuleProcessorInterface
{
    const RULE_NAME_GROUP_REQUIRED = 'required_group';

    protected static $copyForTypes = array(
        DateTimeType::class,
        TimeType::class,
        DateType::class,
        BirthdayType::class,
    );

    /**
     * @var bool
     */
    private $useGroupRule;

    public function __construct($useGroupRule = false)
    {
        $this->useGroupRule = $useGroupRule;
    }

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

    private function registerRulesForChildren(FormRuleContextBuilder $formRuleContext, FormView $view, RuleMessage $message = null)
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
            if ($this->useGroupRule && $baseRule->name === RequiredRule::RULE_NAME) {
                $firstView = null;
                $fields = array();
                foreach ($it as $childView) {
                    if ($firstView === null) {
                        $firstView = $childView;
                        continue;
                    }
                    $fields[] = $childView->vars['full_name'];
                }

                $collection = $this->getOrCreateRuleCollection($formRuleContext, $firstView);
                $collection->set($name, clone $baseRule);
                $collection->set(
                    self::RULE_NAME_GROUP_REQUIRED,
                    new TransformerRule(self::RULE_NAME_GROUP_REQUIRED, $fields, $message)
                );
                continue;
            }

            // Prepare a new rule
            $rule = clone $baseRule;
            foreach ($it as $childView) {
                $collection = $this->getOrCreateRuleCollection($formRuleContext, $childView);

                if ($collection->containsKey($name)) {
                    $childRule = $collection[$name];
                    $childRule->message = $rule->message;
                    $childRule->conditions = $rule->conditions;
                    if ($childRule instanceof ConstraintRule && $rule instanceof ConstraintRule) {
                        $childRule->groups = array_unique(
                            array_merge($childRule->groups, $rule->groups)
                        );
                    }
                } else {
                    $collection->set($name, $rule);
                }

                $rule = clone $rule;
                $rule->message = $message;
                $rule->conditions[] = new FieldDependency($childView->vars['full_name']);
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

    /**
     * @param FormRuleContextBuilder $formRuleContext
     * @param FormView|string $view
     * @return RuleCollection|null
     */
    private function getOrCreateRuleCollection(FormRuleContextBuilder $formRuleContext, $view)
    {
        $collection = $formRuleContext->get($view);
        if ($collection !== null) {
            return $collection;
        }
        $formRuleContext->add($view, new RuleCollection());

        return $formRuleContext->get($view);
    }
}
