<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MaxRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MinRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\NumberRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Boekkooi\Bundle\JqueryValidationBundle\Form\TransformerRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormViewRecursiveIterator;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class DateTimeToArrayTransformerPass extends ViewTransformerProcessor
{
    public function process(FormRuleProcessorContext $context, FormRuleContextBuilder $formRuleContext)
    {
        $form = $context->getForm();
        $formConfig = $form->getConfig();
        if (!$formConfig->getCompound()) {
            return;
        }

        $transformer = $this->findTransformer($formConfig, 'Symfony\\Component\\Form\\Extension\\Core\\DataTransformer\\DateTimeToArrayTransformer');
        if ($transformer === null) {
            return;
        }
        $view = $context->getView();

        /** @var FormView[] $it */
        $it = new \RecursiveIteratorIterator(
            new FormViewRecursiveIterator($view->getIterator()),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        $invalidMessage = $this->getFormRuleMessage($formConfig);

        $depends = array();
        foreach ($it as $childView) {
            $this->addNumberCheck(
                $childView,
                $formRuleContext->get($childView),
                $invalidMessage,
                $depends
            );
            $depends[] = $childView->vars['full_name'];
        }
    }

    private function addNumberCheck(FormView $view, RuleCollection $rules, RuleMessage $message, array $depends)
    {
        // Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer
        switch ($view->vars['name']) {
            case 'year':
                $rules->set(
                    NumberRule::RULE_NAME,
                    new TransformerRule(
                        NumberRule::RULE_NAME,
                        true,
                        $message
                    ),
                    array(Constraint::DEFAULT_GROUP)
                    , $depends
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
            new TransformerRule( MinRule::RULE_NAME, $min, $message, array(Constraint::DEFAULT_GROUP), $depends )
        );
        $rules->set(
            MaxRule::RULE_NAME,
            new TransformerRule( MaxRule::RULE_NAME, $max, $message, array(Constraint::DEFAULT_GROUP), $depends )
        );
    }
}
