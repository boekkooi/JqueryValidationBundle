<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Util;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ResolvedFormTypeInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
final class FormHelper
{
    public static function isType(ResolvedFormTypeInterface $type, $typeName)
    {
        do {
            if ($type->getName() === $typeName) {
                return true;
            }
            $type = $type->getParent();
        } while ($type !== null);

        return false;
    }

    /**
     * @param FormView|string $form
     * @return string
     */
    public static function getFormName($form)
    {
        if ($form instanceof FormView && isset($form->vars['full_name'])) {
            return $form->vars['full_name'];
        }
        if (is_string($form)) {
            return $form;
        }

        throw new \InvalidArgumentException();
    }

    /**
     * @param FormView $view
     * @return FormView
     */
    public static function getViewRoot(FormView $view)
    {
        $root = $view;
        while ($root->parent !== null) {
            $root = $root->parent;
        }

        return $root;
    }

    /**
     * Retrieve the (jquery) validation groups that are configured for the given FormInterface instance.
     *
     * @param FormInterface $form
     * @return array|null|false
     */
    public static function getValidationGroups(FormInterface $form)
    {
        $cfg = $form->getConfig();

        if ($cfg->hasOption('jquery_validation_groups')) {
            $groups = $cfg->getOption('jquery_validation_groups');
        } else {
            $groups = $cfg->getOption('validation_groups');
        }

        if ($groups === null || $groups === false) {
            return $groups;
        }

        if (!is_string($groups) && is_callable($groups)) {
            throw new \RuntimeException('Callable validation_groups are not supported. Disable jquery_validation or set jquery_validation_groups');
        }

        return (array) $groups;
    }
}
