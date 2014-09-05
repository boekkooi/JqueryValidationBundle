<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RequiredViewPass implements FormPassInterface
{
    public function process(FormRuleCollection $collection, ConstraintCollection $constraints)
    {
        // TODO check if not[blank] etc is set
        if (isset($collection->getView()->vars['required'])) {
            $collection->getView()->vars['required'] = false;
        }
    }

}
