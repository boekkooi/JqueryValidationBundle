<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ConstraintGroupFilterPass implements FormPassInterface
{
    public function process(FormRuleCollection $collection, $constraints)
    {
        // TODO look for custom group

        /** @var array|Closure $groups */
        $groups = $collection->getForm()->getConfig()->getOption('validation_groups', array(Constraint::DEFAULT_GROUP));
        $groups = self::resolveValidationGroups($groups, $collection->getForm());

        // TODO add clicked/button validation groups

        /** @var Constraint[] $constraints */
        $removeKeys = [];
        foreach ($constraints as $i => $constraint) {
            foreach ($groups as $group) {
                if (in_array($group, $constraint->groups)) {
                    continue 2;
                }
            }
            $removeKeys[] = $i;
        }

        foreach ($removeKeys as $key) {
            unset($constraints[$key]);
        }
    }

    /**
     * Returns the validation groups of the given form.
     *
     * @param  FormInterface $form The form.
     *
     * @return array The validation groups.
     */
    private static function getValidationGroups(FormInterface $form)
    {
        // Determine the clicked button of the complete form tree
        $clickedButton = null;

        if (method_exists($form, 'getClickedButton')) {
            $clickedButton = $form->getClickedButton();
        }

        if (null !== $clickedButton) {
            $groups = $clickedButton->getConfig()->getOption('validation_groups');

            if (null !== $groups) {
                return self::resolveValidationGroups($groups, $form);
            }
        }

        do {
            $groups = $form->getConfig()->getOption('validation_groups');

            if (null !== $groups) {
                return self::resolveValidationGroups($groups, $form);
            }

            $form = $form->getParent();
        } while (null !== $form);

        return array(Constraint::DEFAULT_GROUP);
    }

    /**
     * Post-processes the validation groups option for a given form.
     *
     * @param array|callable $groups The validation groups.
     * @param FormInterface  $form   The validated form.
     *
     * @return array The validation groups.
     *
     * @see \Symfony\Component\Form\Extension\Validator\Constraints\FormValidator::resolveValidationGroups
     */
    private static function resolveValidationGroups($groups, FormInterface $form)
    {
        if (!is_string($groups) && is_callable($groups)) {
            $groups = call_user_func($groups, $form);
        }

        return (array) $groups;
    }
}