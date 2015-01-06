<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\NumberRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\NumberRule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class NumberRuleTest extends BaseConstraintMapperTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new NumberRule();
    }

    public function provide_supported_constraints()
    {
        return array(
            array(new Constraints\Range(array('min' => 1))),
            array(new Constraints\Type(array('type' => 'Int'))),
            array(new Constraints\Type(array('type' => 'integer'))),
            array(new Constraints\Type(array('type' => 'float'))),
            array(new Constraints\Type(array('type' => 'double'))),
        );
    }

    public function provide_constraint_rule_expectation()
    {
        return array(
            array(
                new Constraints\Range(array('invalidMessage' => 'msg', 'min' => 1)),
                new Rule('number', true, new RuleMessage('msg'), array(Constraint::DEFAULT_GROUP))
            ),
            array(
                new Constraints\Type(array('message' => 'msg', 'type' => 'double')),
                new Rule('number', true, new RuleMessage('msg', array('{{ type }}' => 'double')), array(Constraint::DEFAULT_GROUP))
            )
        );
    }
    public function provide_unsupported_constraints()
    {
        return array(
            array(new Constraints\NotBlank()),
            array(new Constraints\NotNull())
        );
    }
}
