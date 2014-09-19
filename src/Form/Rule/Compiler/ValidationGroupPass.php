<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\FormContext;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ValidationGroupPass implements FormPassInterface
{
    public function process(FormRuleCollection $collection, FormContext $context)
    {
        if ($context->getConstraints()->count() === 0 || $context->isDisabled()) {
            return;
        }

        $rootView = $this->getRootView($collection);

        if (empty($rootView->vars['jquery_validation_groups'])) {
            return;
        }

        $groups = $rootView->vars['jquery_validation_groups'];
        $name = $collection->getView()->vars['full_name'];

        $context->getGroups()->add(
            $this->getValidationGroups($name, $groups)
        );
    }

    private function getValidationGroups($formName, array $validationGroups)
    {
        // Check if the element is know before invoking expensive stuff
        if (isset($validationGroups[$formName])) {
            return $validationGroups[$formName];
        }

        // Find the closest validation_groups
        // I'm not sure if PropertyPath is the best way to go, maybe we need something simpler?
        $path = new PropertyPath($formName);
        do {
            $formName = (string) $path;
            if (isset($validationGroups[$formName]) && $validationGroups[$formName] !== null) {
                return $validationGroups[$formName];
            }

            $path = $path->getParent();
        } while ($path !== null);

        return array(Constraint::DEFAULT_GROUP);
    }

    private function getRootView(FormRuleCollection $collection)
    {
        if ($collection->isRoot()) {
            return $collection->getView();
        }

        return $collection->getRoot()->getView();
    }
}
