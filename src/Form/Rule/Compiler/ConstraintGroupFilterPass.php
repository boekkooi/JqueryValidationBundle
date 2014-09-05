<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ConstraintGroupFilterPass implements FormPassInterface
{
    public function process(FormRuleCollection $collection, ConstraintCollection $constraints)
    {
        $groups = $this->getValidationGroups($collection);
        $groups = self::resolveValidationGroups($groups, $collection->getForm());

        // TODO add clicked/button validation group support

        /** @var Constraint[] $constraints */
        foreach ($constraints as $i => $constraint) {
            foreach ($groups as $group) {
                if (in_array($group, $constraint->groups)) {
                    continue 2;
                }
            }

            $constraints->remove($i);
        }
    }

    private static function getValidationGroups(FormRuleCollection $collection)
    {
        $groups = array(Constraint::DEFAULT_GROUP);

        // Start walking at the root of the form
        $form = $collection->isRoot() ? $collection->getForm() : $collection->getRoot()->getForm();
        $path = (new PropertyPath($collection->getView()->vars['full_name']))->getIterator();
        $path->rewind();
        $path->next();

        // Walk to the current element
        while ($path->valid()) {
            $childGroups = $form->getConfig()->getOption('validation_groups');
            if ($childGroups !== null) {
                $groups = $childGroups;
            }

            $childName = $path->current();
            if ($form->has($childName)) {
                $form = $form->get($childName);
            } elseif (($prototype = $form->getConfig()->getAttribute('prototype')) && ($childName === $prototype->getName())) {
                $form = $prototype;
            } elseif ($form->getConfig()->getOption('csrf_field_name') === $childName) {
                return $groups;
            } else {
                // TODO remove exception before 1.0
                // This really should not happen and maybe it would be better not to throw a exception but just return the last groups.
//                var_dump($formName, $form);die;
                throw new \RuntimeException();
            }
            $path->next();
        }

        return $groups;
    }

//    /**
//     * Returns the validation groups of the given form.
//     *
//     * @param  FormInterface $form The form.
//     *
//     * @return array The validation groups.
//     */
//    private static function getValidationGroups(FormInterface $form)
//    {
//        // Determine the clicked button of the complete form tree
//        $clickedButton = null;
//
//        if (method_exists($form, 'getClickedButton')) {
//            $clickedButton = $form->getClickedButton();
//        }
//
//        if (null !== $clickedButton) {
//            $groups = $clickedButton->getConfig()->getOption('validation_groups');
//
//            if (null !== $groups) {
//                return self::resolveValidationGroups($groups, $form);
//            }
//        }
//
//        do {
//            $groups = $form->getConfig()->getOption('validation_groups');
//
//            if (null !== $groups) {
//                return self::resolveValidationGroups($groups, $form);
//            }
//
//            $form = $form->getParent();
//        } while (null !== $form);
//
//        return array(Constraint::DEFAULT_GROUP);
//    }

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
            // TODO figout a way to deal with this
            var_dump($groups);die;
            $groups = call_user_func($groups, $form);
        }

        return (array) $groups;
    }
}
