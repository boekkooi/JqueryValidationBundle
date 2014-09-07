<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
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

        // If the validation group is false we ignore everything
        if ($groups === false) {
            $constraints->clear();
            return;
        }

        // Merge with button groups
        $groups = array_merge($groups, $this->getSubmitValidationGroups($collection));

        // Filter the constraints
        foreach ($constraints as $i => $constraint) {
            foreach ($groups as $group) {
                if (in_array($group, $constraint->groups)) {
                    continue 2;
                }
            }

            $constraints->remove($i);
        }
    }

    /**
     * @param FormRuleCollection $collection
     * @return array
     */
    private function getValidationGroups(FormRuleCollection $collection)
    {
        $rootView = $collection->isRoot() ? $collection->getView() : $collection->getRoot()->getView();

        $path = new PropertyPath($collection->getView()->vars['full_name']);
        while ($path !== null) {
            $name = (string)$path;
            if (isset($rootView->vars['jquery_validation_groups'][$name])) {
                return $rootView->vars['jquery_validation_groups'][$name];
            }
            $path = $path->getParent();
        }
        return array(Constraint::DEFAULT_GROUP);
    }

    private function getSubmitValidationGroups(FormRuleCollection $collection)
    {
        $rootView = $collection->isRoot() ? $collection->getView() : $collection->getRoot()->getView();
        if (!isset($rootView->vars['jquery_validation_buttons'])) {
            return array();
        }

        $groups = array();
        foreach ($rootView->vars['jquery_validation_buttons'] as $name) {
            if (!isset($rootView->vars['jquery_validation_groups'][$name])) {
                continue;
            }

            $btnGroups = $rootView->vars['jquery_validation_groups'][$name];
            if (!is_array($groups)) {
                continue;
            }

            $groups = array_merge(
                $groups,
                $btnGroups
            );
        }

        return $groups;
    }

//    private static function getValidationGroups(FormRuleCollection $collection)
//    {
//        // TODO cache parent's etc
//        $groups = array(Constraint::DEFAULT_GROUP);
//
//        // Start walking at the root of the form
//        $form = $collection->isRoot() ? $collection->getForm() : $collection->getRoot()->getForm();
//        $path = (new PropertyPath($collection->getView()->vars['full_name']))->getIterator();
//        $path->rewind();
//        $path->next();
//
//        // Walk to the current element
//        while ($path->valid()) {
//            if (($childGroups = self::getValidationGroupsOption($form)) !== null) {
//                $groups = $childGroups;
//            }
//
//            $childName = $path->current();
//            if ($form->has($childName)) {
//                $form = $form->get($childName);
//            } elseif (($prototype = $form->getConfig()->getAttribute('prototype')) && ($childName === $prototype->getName())) {
//                $form = $prototype;
//            } elseif ($form->getConfig()->getOption('csrf_field_name') === $childName) {
//                return $groups;
//            } else {
//                // TODO remove exception before 1.0
//                // This really should not happen and maybe it would be better not to throw a exception but just return the last groups.
////                var_dump($formName, $form);die;
//                throw new \RuntimeException();
//            }
//            $path->next();
//        }
//
//        return self::resolveValidationGroups($groups, $collection->getForm());
//    }
}
