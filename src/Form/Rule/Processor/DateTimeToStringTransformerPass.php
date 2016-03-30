<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MaxRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MinRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\TransformerRule;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToRfc3339Transformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
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
    private $useTime;

    public function __construct($useTimeRule = false)
    {
        $this->useTime = $useTimeRule;
    }

    public function process(FormRuleProcessorContext $context, FormRuleContextBuilder $formRuleContext)
    {
        $form = $context->getForm();
        $formConfig = $form->getConfig();
        if ($formConfig->getCompound()) {
            return;
        }

        if (
            $this->findTransformer($formConfig, DateTimeToStringTransformer::class) === null &&
            $this->findTransformer($formConfig, DateTimeToRfc3339Transformer::class) === null
        ) {
            return;
        }

        $formView = $context->getView();
        $formTypeClass = get_class($formConfig->getType()->getInnerType());

        switch ($formTypeClass) {
            case TimeType::class:
                $this->processTime($formView, $formConfig, $formRuleContext);

                return;
            case BirthdayType::class:
            case DateType::class:
                $this->processDate($formView, $formConfig, $formRuleContext);

                return;
            case DateTimeType::class:
                $this->processDateTime($formView, $formConfig, $formRuleContext);

                return;
        }
    }

    private function processTime(FormView $view, FormConfigInterface $config, FormRuleContextBuilder $context)
    {
        $rules = new RuleCollection();
        if ($config->getOption('with_minutes')) {
            // Only add time rule if additional rules are enabled
            if ($this->useTime) {
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
