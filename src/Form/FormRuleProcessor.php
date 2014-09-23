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
    protected $resolvers = array();

    public function __construct(array $resolvers)
    {
        foreach ($resolvers as $resolver) {
            $this->addResolver($resolver);
        }
    }

    public function addResolver(FormRuleProcessorInterface $resolver)
    {
        $this->resolvers[] = $resolver;
    }

    public function process(FormRuleProcessorContext $processContext, FormRuleContextBuilder $formRulesCollection)
    {
        foreach ($this->resolvers as $resolver) {
            $resolver->process($processContext, $formRulesCollection);
        }
    }
}
