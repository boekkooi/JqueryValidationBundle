<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\FormContext;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RuleCollector implements FormPassInterface
{
    /**
     * @var FormPassInterface[]
     */
    private $passes;

    /**
     * @param FormPassInterface[] $passes
     */
    public function __construct(array $passes)
    {
        $this->passes = $passes;
    }

    public function process(FormRuleCollection $collection, FormContext $context)
    {
        foreach ($this->passes as $pass) {
            $pass->process($collection, $context);
        }
    }
}
