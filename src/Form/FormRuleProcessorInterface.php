<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
interface FormRuleProcessorInterface
{
    public function process(FormRuleProcessorContext $processContext, FormRuleContextBuilder $formRuleContext);
}
