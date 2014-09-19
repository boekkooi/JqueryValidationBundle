<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\FormContext;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RequiredViewPass implements FormPassInterface
{
    protected static $requiredConstraintClasses = array(
        'Symfony\Component\Validator\Constraints\NotNull',
        'Symfony\Component\Validator\Constraints\NotBlank',
        'Symfony\Component\Validator\Constraints\Required'
    );

    public function process(FormRuleCollection $collection, FormContext $context)
    {
        $view = $collection->getView();

        // Check if the field is really required according to HTML validation
        // (aka the required for symfony form means it needs to be submitted but maybe null or "")
        foreach ($context as $constraint) {
            if (in_array(get_class($constraint), static::$requiredConstraintClasses)) {
                $view->vars['required'] = true;

                return;
            }
        }

        $view->vars['required'] = false;
    }

}
