<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MinRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MinRule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class MinRuleTest extends BaseConstraintMapperTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new MinRule();
    }

    public function provide_supported_constraints()
    {
        return array(
            array(new Constraints\GreaterThan(1)),
            array(new Constraints\GreaterThanOrEqual(1)),
            array(new Constraints\Range(array('min' => 1)))
        );
    }

    public function provide_constraint_rule_expectation()
    {
        return array(
            array(
                new Constraints\GreaterThan(array('value' => 1, 'message' => 'msg')),
                new Rule('min', 2, new RuleMessage('msg', array('{{ compared_value }}' => 1)), array(Constraint::DEFAULT_GROUP))
            ),
            array(
                new Constraints\GreaterThanOrEqual(array('value' => 1, 'message' => 'my_msg')),
                new Rule('min', 1, new RuleMessage('my_msg', array('{{ compared_value }}' => 1)), array(Constraint::DEFAULT_GROUP))
            ),
            array(
                new Constraints\Range(array('min' => 3, 'minMessage' => 'range_msg', 'groups' => array('my_validation_group'))),
                new Rule('min', 3, new RuleMessage('range_msg', array('{{ limit }}' => 3)), array('my_validation_group'))
            )
        );
    }

    public function provide_unsupported_constraints()
    {
        return array(
            array(new Constraints\Length(1)),
            array(new Constraints\Range(array('max' => 1)))
        );
    }
}
