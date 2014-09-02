<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;

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

    public function process(FormRuleCollection $collection, $constraints)
    {
        foreach ($this->passes as $pass) {
            $pass->process($collection, $constraints);
        }
    }
}
