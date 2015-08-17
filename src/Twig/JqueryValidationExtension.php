<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Twig;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;
use Symfony\Component\Form\FormView;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class JqueryValidationExtension extends Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
             new Twig_SimpleFunction(
                'form_jquery_validation',
                array($this, 'renderJavascript'),
                array('needs_environment' => true, 'pre_escape' => array('html', 'js'), 'is_safe' => array('html', 'js'))
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'boekkooi_jquery_validation';
    }

    public function renderJavascript(\Twig_Environment $twig, FormView $view)
    {
        if (!isset($view->vars['rule_context'])) {
            return '';
        }
        /** @var \Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContext $rootContext */
        $template = 'BoekkooiJqueryValidationBundle:Form:form_validate.js.twig';
        $rootContext = $context = $view->vars['rule_context'];
        $rootView = $view;

        // The given view is not the root form
        if ($view->parent !== null) {
            $template = 'BoekkooiJqueryValidationBundle:Form:dynamic_validate.js.twig';
            $rootView = FormHelper::getViewRoot($view);
            $rootContext = $rootView->vars['rule_context'];
        }

        // Create template variables
        $templateVars = array(
            'form' => $rootView,
            'fields' => $this->fieldRulesViewData($context),
            'validation_groups' => $this->validationGroupsViewData($rootContext),
        );
        $templateVars['enforce_validation_groups'] = count($templateVars['validation_groups']) > 1;
        $templateVars['enabled_validation_groups'] = count($rootContext->getButtons()) === 0 ? $templateVars['validation_groups'] : array();

        // Only add buttons from the root form
        if ($view->parent === null) {
            $templateVars['buttons'] = $this->buttonsViewData($context);
        }

        $js = $twig->render($template, $templateVars);

        return preg_replace('/\s+/', ' ', $js);
    }

    protected function validationGroupsViewData(FormRuleContext $context)
    {
        $it = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($context->getGroups())
        );

        return array_unique(array_filter(iterator_to_array($it, false)));
    }

    /**
     * Transform the buttons in the given form into a array that can easily be used by twig.
     *
     * @param FormRuleContext $context
     * @return array
     */
    protected function buttonsViewData(FormRuleContext $context)
    {
        $buttonNames = $context->getButtons();

        $buttons = array();
        foreach ($buttonNames as $name) {
            $groups = $context->getGroup($name);

            $buttons[] = array(
                'name' => $name,
                'cancel' => count($groups) === 0,
                'validation_groups' => $groups,
            );
        }

        return $buttons;
    }

    protected function fieldRulesViewData(FormRuleContext $context)
    {
        $fields = array();
        foreach ($context->all() as $name => $rules) {
            $fields[] = array(
                'name' => $name,
                'rules' => $rules,
            );
        }

        return $fields;
    }
}
