<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\FormContext;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
interface FormPassInterface
{
    public function process(FormRuleCollection $collection, FormContext $context);
}
