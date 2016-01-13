<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Util;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\InvalidArgumentException;
use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Boekkooi\Bundle\JqueryValidationBundle\Exception\UnsupportedException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ResolvedFormTypeInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
final class FormHelper
{
    public static function isSymfony3Compatible()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix');
    }

    public static function isSymfony2Compatible()
    {
        return method_exists(ResolvedFormTypeInterface::class, 'getName');
    }

    public static function isType(ResolvedFormTypeInterface $type, $typeName)
    {
        do {
            if (
                self::isSymfony3Compatible() &&
                get_class($type->getInnerType()) === $typeName
            ) {
                return true;
            }

            if (
                self::isSymfony2Compatible() &&
                $type->getName() === $typeName
            ) {
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

        throw new InvalidArgumentException('Expected form to be a string or instance of FormView with a full_name.');
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

        # Is the default validation group used
        if ($groups === null) {
            return array(Constraint::DEFAULT_GROUP);
        }

        # Is the validation suppressed
        if ($groups === false) {
            return array();
        }

        # Is a unsupported group used
        if (!is_string($groups) && is_callable($groups)) {
            throw new UnsupportedException('Callable validation_groups are not supported. Disable jquery_validation or set jquery_validation_groups');
        }

        return (array) $groups;
    }

    public static function generateCssSelector(FormView $view)
    {
        $vars = $view->vars;
        if (!empty($vars['attr']['id'])) {
            return trim(sprintf('#%s', $vars['attr']['id']));
        }

        if (isset($vars['full_name'])) {
            $root = $view;
            while ($root->parent !== null) {
                $root = $root->parent;
            }

            if ($view === $root) {
                return sprintf('form[name="%s"]', $vars['full_name']);
            }

            $formSelector = self::generateCssSelector($root);

            if ($vars['compound'] && !empty($vars['id'])) {
                return trim(sprintf('%s #%s', $formSelector, $vars['id']));
            }
            return trim(sprintf('%s *[name="%s"]', $formSelector, $vars['full_name']));
        }

        throw new LogicException();
    }
}
