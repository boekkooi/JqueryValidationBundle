<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Twig;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyPath;
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
        if (!isset($view->vars['jquery_validation_rules'])) {
            return '';
        }

        /** @var \Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection $collection */
        $collection = $view->vars['jquery_validation_rules'];

        if (!$collection->isRoot()) {
            $rootView = $collection->getRoot()->getView();

            return $twig->render('BoekkooiJqueryValidationBundle:Form:dynamic_validate.js.twig', array(
                'form' => $rootView,
                'fields' => $this->fieldRulesViewData($collection),
                'validation_groups' => $this->validationGroupsViewData($rootView),
                'enforce_validation_groups' => isset($rootView->vars['jquery_validation_buttons']) && count($rootView->vars['jquery_validation_buttons']) > 0
            ));
        }

        return $twig->render('BoekkooiJqueryValidationBundle:Form:form_validate.js.twig', array(
            'form' => $collection->getView(),
            'fields' => $this->fieldRulesViewData($collection),
            'buttons' => $this->buttonsViewData($view),
            'validation_groups' => $this->validationGroupsViewData($view),
            'enforce_validation_groups' => isset($view->vars['jquery_validation_buttons']) && count($view->vars['jquery_validation_buttons']) > 0
        ));
    }

    protected function validationGroupsViewData(FormView $view)
    {
        $it = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($view->vars['jquery_validation_groups'])
        );

        return array_unique(array_filter(iterator_to_array($it, false)));
    }

    /**
     * Transform the buttons in the given form into a array that can easily be used by twig.
     *
     * @param FormView $view
     * @return array
     */
    protected function buttonsViewData(FormView $view)
    {
        $buttons = array();
        if (empty($view->vars['jquery_validation_buttons'])) {
            return $buttons;
        }

        $validationGroups = $view->vars['jquery_validation_groups'];
        foreach ($view->vars['jquery_validation_buttons'] as $name) {
            $groups = isset($validationGroups[$name]) ? $validationGroups[$name] : null;

            // If the form group is null used the closest validation group
            if ($groups === null) {
                $path = new PropertyPath($name);
                do {
                    if (isset($validationGroups[(string) $path]) && ($groups = $validationGroups[(string) $path]) !== null) {
                        $groups = $validationGroups[(string) $path];
                    }
                    $path = $path->getParent();
                } while ($path !== null && $groups === null);
            }

            // A cancel button
            $cancel = false;
            if (empty($groups)) {
                $cancel = true;
                $groups = array();
            }

            // Create the view array
            $buttons[] = array(
                'name' => $name,
                'cancel' => $cancel,
                'validation_groups' => array_unique($groups)
            );
        }

        return $buttons;
    }


    protected function fieldRulesViewData(FormRuleCollection $collection)
    {
        $fields = [];
        foreach ($collection as $name => $rules) {
            $fields[] = array(
                'name' => $name,
                'rules' => $rules
            );
        }

        return $fields;
    }
}
