<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\IbanRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\IbanRule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class IbanRuleTest extends BaseConstraintMapperTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new IbanRule(true);
    }

    public function provide_supported_constraints()
    {
        return array(
            array(new Constraints\Iban()),
        );
    }

    public function provide_constraint_rule_expectation()
    {
        return array(
            array(
                new Constraints\Iban(array('message' => 'msg', 'groups' => array('iban_group'))),
                new Rule('iban', true, new RuleMessage('msg'), array('iban_group'))
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
        $mappingRule = new IbanRule(false);
        $this->assertFalse($mappingRule->supports($constraint, $this->form));
    }
}
