<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Extension;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ButtonTypeExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var FormInterface | ClickableInterface $form */
        if (!$form instanceof ClickableInterface) {
            return;
        }

        $viewRoot = FormHelper::getViewRoot($view);
        if (!$this->hasRuleBuilderContext($viewRoot)) {
            return;
        }

        /** @var FormRuleContextBuilder $context */
        $context = $viewRoot->vars['rule_builder'];
        $context->addButton($view, FormHelper::getValidationGroups($form));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FormHelper::isSymfony3Compatible() ? ButtonType::class : 'button';
    }

    protected function hasRuleBuilderContext(FormView $view)
    {
        return isset($view->vars['rule_builder']) && $view->vars['rule_builder'] instanceof FormRuleContextBuilder;
    }
}
