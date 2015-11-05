<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class CountConstraintTransformerPass implements FormRuleProcessorInterface
{
    const RULE_NAME_MAX = 'collection_count_max';
    const RULE_NAME_MIN = 'collection_count_min';
    const RULE_NAME_EXACT = 'collection_count_exact';

    const COUNT_CONSTRAINT_CLASS = 'Symfony\Component\Validator\Constraints\Count';

    /**
     * @var bool
     */
    private $active;

    public function __construct($active = true)
    {
        $this->active = $active;
    }

    public function process(FormRuleProcessorContext $processContext, FormRuleContextBuilder $formRuleContext)
    {
        if (!$this->active) {
            return;
        }

        $form = $processContext->getForm();
        $view = $processContext->getView();

        // Check if this there is a prototype
        /** @var FormInterface|null $prototype */
        $prototype = $form->getConfig()->getAttribute('prototype');
        if (!$prototype || !isset($view->vars['prototype'])) {
            return;
        }

        // Ensure that we have a count constraint
        $constraint = $this->getCountConstraint($processContext->getConstraints());
        if ($constraint === null) {
            return;
        }

        // Create a count selector
        $elementSelector = $this->resolveCountSelector($view, $form);

        // Register rules
        $rules = new RuleCollection();
        $this->registerRule($constraint, $elementSelector, $rules);
        $formRuleContext->add($view, $rules);

        // Add custom attribute for jquery validation
        $view->vars['attr']['data-validateable'] = $view->vars['full_name'];
    }

    /**
     * @param ConstraintCollection $constraints
     * @return \Symfony\Component\Validator\Constraints\Count|null
     */
    private function getCountConstraint(ConstraintCollection $constraints)
    {
        foreach ($constraints as $constraint) {
            if (get_class($constraint) === self::COUNT_CONSTRAINT_CLASS) {
                return $constraint;
            }
        }

        return null;
    }

    private function registerRule(Constraint $constraint, $elementSelector, RuleCollection $collection)
    {
        /** @var \Symfony\Component\Validator\Constraints\Count $constraint */
        if ($constraint->min === $constraint->max) {
            $collection->set(
                self::RULE_NAME_EXACT,
                new ConstraintRule(
                    self::RULE_NAME_EXACT,
                    array(
                        'limit' => $constraint->min,
                        'field' => $elementSelector
                    ),
                    new RuleMessage(
                        $constraint->exactMessage,
                        array('{{ limit }}' => $constraint->min),
                        (int) $constraint->min
                    ),
                    $constraint->groups
                )
            );
            return;
        }

        if ($constraint->min !== null) {
            $collection->set(
                self::RULE_NAME_MIN,
                new ConstraintRule(
                    self::RULE_NAME_MIN,
                    array(
                        'min' => $constraint->min,
                        'field' => $elementSelector
                    ),
                    new RuleMessage(
                        $constraint->minMessage,
                        array('{{ limit }}' => $constraint->min),
                        (int) $constraint->min
                    ),
                    $constraint->groups
                )
            );
        }

        if ($constraint->max !== null) {
            $collection->set(
                self::RULE_NAME_MAX,
                new ConstraintRule(
                    self::RULE_NAME_MAX,
                    array(
                        'max' => $constraint->max,
                        'field' => $elementSelector
                    ),
                    new RuleMessage(
                        $constraint->maxMessage,
                        array('{{ limit }}' => $constraint->max),
                        (int) $constraint->max
                    ),
                    $constraint->groups
                )
            );
        }
    }

    private function resolveCountSelector(FormView $view, FormInterface $form)
    {
        $prototypeView = $view->vars['prototype'];
        $prototypeReplacementName = $form->getConfig()->getOption('prototype_name', '__name__');

        $collectionSelector = $this->prototypeCssSelectorPart(
            $this->findClosestField($prototypeView),
            $prototypeReplacementName
        );

        return FormHelper::generateCssSelector($view) . ' ' . $collectionSelector;
    }

    private function findClosestField($view)
    {
        if (!$view->vars['compound']) {
            return $view;
        }

        // First find on the current level
        foreach ($view->children as $child) {
            if ($view->vars['compound']) {
                continue;
            }

            return $child;
        }

        // Else just take the first one with a id
        foreach ($view->children as $child) {
            if (isset($view->vars['id']) || isset($vars['attr']['id'])) {
                return $child;
            }
        }

        throw new LogicException();
    }

    private function prototypeCssSelectorPart(FormView $view, $prototypeName)
    {
        $vars = $view->vars;

        if ($vars['compound']) {
            $attr = 'id';
            $detect = isset($vars['attr']['id']) ? $vars['attr']['id'] : $vars['id'];
        } else {
            $attr = 'name';
            $detect = $vars['full_name'];
        }

        $parts = explode($prototypeName, $detect);

        $cssParts = array(
            sprintf('*[%s^="%s"]', $attr, array_shift($parts)),
            sprintf('[%s$="%s"]', $attr, array_pop($parts))
        );
        foreach ($parts as $part) {
            $cssParts[] = sprintf('[%s~="%s"]', $attr, $part);
        }
        return implode('', $cssParts);
    }
}
