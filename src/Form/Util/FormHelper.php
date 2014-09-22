<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Util;

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
}
