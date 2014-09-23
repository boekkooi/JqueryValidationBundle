<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCompilerInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Symfony\Component\PropertyAccess\PropertyPath;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class GroupNullResolverPass implements FormRuleCompilerInterface
{
    /**
     * @param FormRuleContextBuilder $context
     */
    public function compile(FormRuleContextBuilder $context)
    {
        $formGroups = $context->getGroups();
        foreach ($formGroups as $name => $groups) {
            if ($groups !== null) {
                continue;
            }

            // Find the closest validation group
            $path = new PropertyPath($name);
            do {
                $parentName = (string) $path;
                if (isset($formGroups[$parentName]) && ($groups = $formGroups[$parentName]) !== null) {
                    $context->addGroup($name, $groups);
                    break;
                }
                $path = $path->getParent();
            } while ($path !== null);
        }
    }
}
