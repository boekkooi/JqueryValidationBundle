<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ConstraintMappingPass implements FormRuleProcessorInterface
{
    /**
     * @var ConstraintMapperInterface[]
     */
    protected $mappers = array();

    public function process(FormRuleProcessorContext $processContext, FormRuleContextBuilder $formRuleContext)
    {
        $constraints = $processContext->getConstraints();
        $form = $processContext->getForm();

        $collection = new RuleCollection();
        foreach ($this->mappers as $mapper) {
            foreach ($constraints as $constraint) {
                if (!$mapper->supports($constraint, $form)) {
                    continue;
                }

                $mapper->resolve($constraint, $form, $collection);
            }
        }

        $formRuleContext->add(
            $processContext->getView(),
            $collection
        );
    }

    public function addMapper(ConstraintMapperInterface $mapper)
    {
        $this->mappers[] = $mapper;
    }
}
