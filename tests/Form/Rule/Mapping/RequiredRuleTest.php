<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\RequiredRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\RequiredRule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RequiredRuleTest extends BaseConstraintMapperTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new RequiredRule();
    }

    public function provide_supported_constraints()
    {
        return array(
            array(new Constraints\NotBlank()),
            array(new Constraints\NotNull())
        );
    }

    public function provide_constraint_rule_expectation()
    {
        return array(
            array(
                new Constraints\NotBlank(array('message' => 'msg_blank')),
                new Rule('required', true, new RuleMessage('msg_blank', array()), array(Constraint::DEFAULT_GROUP))
            ),
            array(
                new Constraints\NotNull(array('message' => 'msg_null')),
                new Rule('required', true, new RuleMessage('msg_null', array()), array(Constraint::DEFAULT_GROUP))
            )
        );
    }
    public function provide_unsupported_constraints()
    {
        return array(
            array(new Constraints\Required()),
            array(new Constraints\Range(array('max' => 1)))
        );
    }
}
