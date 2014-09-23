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
            )
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

        /** @var \Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContext $context */
        $context = $view->vars['rule_context'];

        if ($view->parent !== null) {
            $rootView = FormHelper::getViewRoot($view);

            $js = $twig->render('BoekkooiJqueryValidationBundle:Form:dynamic_validate.js.twig', array(
                'form' => $rootView,
                'fields' => $this->fieldRulesViewData($context),
                'validation_groups' => $this->validationGroupsViewData($context),
                'enforce_validation_groups' => count($context->getButtons()) > 0
            ));
        } else {
            $js = $twig->render('BoekkooiJqueryValidationBundle:Form:form_validate.js.twig', array(
                'form' => $view,
                'fields' => $this->fieldRulesViewData($context),
                'buttons' => $this->buttonsViewData($context),
                'validation_groups' => $this->validationGroupsViewData($context),
                'enforce_validation_groups' => count($context->getButtons()) > 0
            ));
        }

        return preg_replace('/\s+/', ' ' , $js);
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
                'validation_groups' => $groups
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
                'rules' => $rules
            );
        }

        return $fields;
    }
}
