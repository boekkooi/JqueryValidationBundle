<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormRuleProcessor implements FormRuleProcessorInterface
{
    /**
     * @var FormRuleProcessorInterface[]
     */
    protected $processors = array();

    public function __construct(array $processors)
    {
        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
    }

    public function process(FormRuleProcessorContext $processContext, FormRuleContextBuilder $formRuleContext)
    {
        foreach ($this->processors as $processor) {
            $processor->process($processContext, $formRuleContext);
        }
    }

    private function addProcessor(FormRuleProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }
}
