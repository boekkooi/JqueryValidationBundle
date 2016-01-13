<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Condition\FieldDependency;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MaxRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MinRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\NumberRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\RequiredRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\TransformerRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\FormView;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class DateTimeToArrayTransformerPass extends ViewTransformerProcessor
{
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
        if (!$formConfig->getCompound()) {
            return;
        }

        /** @var DateTimeToArrayTransformer $transformer */
        $transformer = $this->findTransformer($formConfig, DateTimeToArrayTransformer::class);
        if ($transformer === null) {
            return;
        }
        $view = $context->getView();
        $fields = $this->getTransformerFields($transformer);
        $invalidMessage = $this->getFormRuleMessage($formConfig);

        $views = array();
        $conditions = array();
        foreach ($fields as $fieldName) {
            $childView = $view->children[$fieldName];

            // Get child rules collection
            $childRules = $formRuleContext->get($childView);
            if ($childRules === null) {
                $formRuleContext->add($childView, new RuleCollection());
                $childRules = $formRuleContext->get($childView);
            }

            // Register rules
            $this->addNumberCheck(
                $childView,
                $childRules,
                $invalidMessage,
                $conditions
            );

            $views[] = FormHelper::getFormName($childView);
            $conditions[] = new FieldDependency($childView);
        }

        if ($this->useGroupRule && count($views) > 1) {
            $rules = $formRuleContext->get(array_shift($views));
            $rules->set(
                CompoundCopyToChildPass::RULE_NAME_GROUP_REQUIRED,
                new TransformerRule(CompoundCopyToChildPass::RULE_NAME_GROUP_REQUIRED, $views, $invalidMessage)
            );
        }
    }

    private function addNumberCheck(FormView $view, RuleCollection $rules, RuleMessage $message = null, array $conditions = array())
    {
        if (!$this->useGroupRule && count($conditions) > 0) {
            $rules->set(
                RequiredRule::RULE_NAME,
                new TransformerRule(
                    RequiredRule::RULE_NAME,
                    true,
                    $message,
                    $conditions
                )
            );
        }

        // Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer
        switch ($view->vars['name']) {
            case 'year':
                $rules->set(
                    NumberRule::RULE_NAME,
                    new TransformerRule(
                        NumberRule::RULE_NAME,
                        true,
                        $message,
                        $conditions
                    )
                );

                return;
            case 'month':
                $min = 1;
                $max = 12;
                break;
            case 'day':
                $min = 1;
                $max = 31;
                break;
            case 'hour':
                $min = 0;
                $max = 23;
                break;
            case 'minute':
            case 'second':
                $min = 0;
                $max = 59;
                break;
            default:
                return;
        }
        $rules->set(
            MinRule::RULE_NAME,
            new TransformerRule(MinRule::RULE_NAME, $min, $message, $conditions)
        );
        $rules->set(
            MaxRule::RULE_NAME,
            new TransformerRule(MaxRule::RULE_NAME, $max, $message, $conditions)
        );
    }

    private function getTransformerFields(DateTimeToArrayTransformer $transformer)
    {
        $property = new \ReflectionProperty(
            DateTimeToArrayTransformer::class,
            'fields'
        );
        $property->setAccessible(true);

        return $property->getValue($transformer);
    }
}
