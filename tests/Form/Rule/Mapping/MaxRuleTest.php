<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MaxRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;


/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MaxRule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class MaxRuleTest extends BaseConstraintMapperTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new MaxRule();
    }

    public function provide_supported_constraints()
    {
        return array(
            array(new Constraints\LessThan(1)),
            array(new Constraints\LessThanOrEqual(1)),
            array(new Constraints\Range(array('max' => 1)))
        );
    }

    public function provide_constraint_rule_expectation()
    {
        return array(
            array(
                new Constraints\LessThan(array('value' => 1, 'message' => 'msg')),
                new Rule('max', 0, new RuleMessage('msg', array('{{ compared_value }}' => 1)), array(Constraint::DEFAULT_GROUP))
            ),
            array(
                new Constraints\LessThanOrEqual(array('value' => 1, 'message' => 'my_msg')),
                new Rule('max', 1, new RuleMessage('my_msg', array('{{ compared_value }}' => 1)), array(Constraint::DEFAULT_GROUP))
            ),
            array(
                new Constraints\Range(array('max' => 3, 'maxMessage' => 'range_msg')),
                new Rule('max', 3, new RuleMessage('range_msg', array('{{ limit }}' => 3)), array(Constraint::DEFAULT_GROUP))
            )
        );
    }

    public function provide_unsupported_constraints()
    {
        return array(
            array(new Constraints\Length(1)),
            array(new Constraints\Range(array('min' => 1)))
        );
    }
}
