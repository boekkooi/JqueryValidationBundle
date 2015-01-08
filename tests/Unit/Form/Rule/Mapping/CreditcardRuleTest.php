<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\CreditcardRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\CreditcardRule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class CreditcardRuleTest extends BaseConstraintMapperTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new CreditcardRule();
    }

    public function provide_supported_constraints()
    {
        return array(
            array(new Constraints\CardScheme('VISA')),
        );
    }

    public function provide_constraint_rule_expectation()
    {
        return array(
            array(
                new Constraints\CardScheme(array('message' => 'msg', 'schemes' => 'VISA')),
                new ConstraintRule('creditcard', true, new RuleMessage('msg'), array(Constraint::DEFAULT_GROUP)),
            ),
        );
    }
    public function provide_unsupported_constraints()
    {
        return array(
            array(new Constraints\NotBlank()),
            array(new Constraints\NotNull()),
        );
    }
}
