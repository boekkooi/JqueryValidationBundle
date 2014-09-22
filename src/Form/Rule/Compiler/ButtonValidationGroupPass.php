<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\FormContext;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\GroupCollection;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ButtonValidationGroupPass implements FormPassInterface
{
    public function process(FormRuleCollection $collection, FormContext $context)
    {
        // No constraints
        if ($context->getConstraints()->count() === 0 || $context->isDisabled()) {
            return;
        }

        $view = $collection->getRoot()->getView();

        // No buttons or validation groups so nothing to do
        if (empty($view->vars['jquery_validation_buttons']) || empty($view->vars['jquery_validation_groups'])) {
            return;
        }

        $groups = $view->vars['jquery_validation_groups'];
        $buttonNames = $view->vars['jquery_validation_buttons'];

        $this->applyConstraintGroups($context->getGroups(), $buttonNames, $groups);
    }

    /**
     * @param ConstraintCollection $constraints
     * @param $buttonNames
     * @param $groups
     */
    private function applyConstraintGroups(GroupCollection $groupCollection, $buttonNames, $groups)
    {
        foreach ($buttonNames as $name) {
            if (!isset($groups[$name])) {
                continue;
            }

            $btnGroups = $groups[$name];
            if (!is_array($groups)) {
                continue;
            }

            $groupCollection->add($btnGroups);
        }
    }
}
