<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MaxRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MinRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\TransformerRule;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class DateTimeToStringTransformerPass extends ViewTransformerProcessor
{
    /**
     * @var bool
     */
    private $includeAdditionalRules;

    public function __construct($includeAdditionalRules = false)
    {
        $this->includeAdditionalRules = $includeAdditionalRules;
    }

    public function process(FormRuleProcessorContext $context, FormRuleContextBuilder $formRuleContext)
    {
        $form = $context->getForm();
        $formConfig = $form->getConfig();
        if ($formConfig->getCompound()) {
            return;
        }

        if (
            $this->findTransformer($formConfig, 'Symfony\\Component\\Form\\Extension\\Core\\DataTransformer\\DateTimeToStringTransformer') === null &&
            $this->findTransformer($formConfig, 'Symfony\\Component\\Form\\Extension\\Core\\DataTransformer\\DateTimeToRfc3339Transformer') === null
        ) {
            return;
        }

        $formView = $context->getView();
        $formTypeClass = get_class($formConfig->getType()->getInnerType());

        switch ($formTypeClass) {
            case 'Symfony\Component\Form\Extension\Core\Type\TimeType':
                $this->processTime($formView, $formConfig, $formRuleContext);

                return;
            case 'Symfony\Component\Form\Extension\Core\Type\DateType':
                $this->processDate($formView, $formConfig, $formRuleContext);

                return;
            case 'Symfony\Component\Form\Extension\Core\Type\DateTimeType':
                $this->processDateTime($formView, $formConfig, $formRuleContext);

                return;
        }
    }

    private function processTime(FormView $view, FormConfigInterface $config, FormRuleContextBuilder $context)
    {
        if ($config->getOption('with_seconds')) {
            return;
        }

        $rules = new RuleCollection();
        if ($config->getOption('with_minutes')) {
            // Only add time rule if additional rules are enabled
            if ($this->includeAdditionalRules) {
                $rules->set(
                    'time',
                    new TransformerRule(
                        'time',
                        true,
                        $this->getFormRuleMessage($config)
                    )
                );
            }
        } else {
            $rules->set(
                MinRule::RULE_NAME,
                new TransformerRule(
                    MinRule::RULE_NAME,
                    0,
                    $this->getFormRuleMessage($config)
                )
            );
            $rules->set(
                MaxRule::RULE_NAME,
                new TransformerRule(
                    MaxRule::RULE_NAME,
                    23,
                    $this->getFormRuleMessage($config)
                )
            );
        }
        $context->add($view, $rules);
    }

    private function processDate(FormView $view, FormConfigInterface $config, FormRuleContextBuilder $context)
    {
        $rules = new RuleCollection();
        $rules->set(
            'dateISO',
            new TransformerRule(
                'dateISO',
                true,
                $this->getFormRuleMessage($config)
            )
        );
        $context->add($view, $rules);
    }

    private function processDateTime(FormView $view, FormConfigInterface $config, FormRuleContextBuilder $context)
    {
        if ($config->getOption('format') !== DateTimeType::HTML5_FORMAT) {
            return;
        }

        $rules = new RuleCollection();
        $rules->set(
            'date',
            new TransformerRule(
                'date',
                true,
                $this->getFormRuleMessage($config)
            )
        );
        $context->add($view, $rules);
    }
}
