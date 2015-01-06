<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\LuhnRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\LuhnRule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class LuhnRuleTest extends BaseConstraintMapperTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new LuhnRule(true);
    }

    public function provide_supported_constraints()
    {
        return array(
            array(new Constraints\Luhn()),
        );
    }

    public function provide_constraint_rule_expectation()
    {
        return array(
            array(
                new Constraints\Luhn(array('message' => 'msg', 'groups' => array('luhn_group'))),
                new Rule('luhn', true, new RuleMessage('msg'), array('luhn_group'))
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

    /**
     * @test
     * @dataProvider provide_constraint_rule_expectation
     */
    public function no_rules_if_disabled(Constraint $constraint)
    {
        $mappingRule = new LuhnRule(false);
        $this->assertFalse($mappingRule->supports($constraint, $this->form));
    }
}
