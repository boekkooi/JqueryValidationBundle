<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
interface FormRuleCompilerInterface
{
    /**
     * @param FormRuleContextBuilder $formRuleContext
     */
    public function compile(FormRuleContextBuilder $formRuleContext);
}
