<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\EmailRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraints;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\EmailRule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class EmailRuleTest extends BaseConstraintMapperTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new EmailRule();
    }

    public function provide_supported_constraints()
    {
        return array(
            array(new Constraints\Email()),
        );
    }

    public function provide_constraint_rule_expectation()
    {
        return array(
            array(
                new Constraints\Email(array('message' => 'msg', 'groups' => array('email_group'))),
                new Rule('email', true, new RuleMessage('msg'), array('email_group'))
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
