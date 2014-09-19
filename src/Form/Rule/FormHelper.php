<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

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
}
