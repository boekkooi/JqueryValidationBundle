<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
interface FormRuleProcessorInterface
{
    /**
     * @param FormRuleProcessorContext $processContext
     * @param FormRuleContextBuilder $formRuleContext
     * @return void
     */
    public function process(FormRuleProcessorContext $processContext, FormRuleContextBuilder $formRuleContext);
}
