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

    public function __construct()
    {
        $this->passes = $this->getFormPasses();
    }

    public function process(FormRuleCollection $collection, $constraints)
    {
        foreach ($this->passes as $pass) {
            $pass->process($collection, $constraints);
        }
    }

    protected function getFormPasses()
    {
        return [
            new Rule\Compiler\ConstraintGroupFilterPass(),
            new Rule\Compiler\RuleCollectionPass(new Rule\ConstraintResolver()),
            new Rule\Compiler\ValueToDuplicatesPass(),
            new Rule\Compiler\CollectionPrototypePass()
        ];
    }
}