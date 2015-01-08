<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\IpRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\IpRule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class IpRuleTest extends BaseConstraintMapperTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new IpRule(true, true, true);
    }

    public function provide_supported_constraints()
    {
        return array(
            array(new Constraints\Ip()),
        );
    }

    public function provide_constraint_rule_expectation()
    {
        return array(
            // Ip v4 only
            array(
                new Constraints\Ip(array('message' => 'msg')),
                new ConstraintRule('ipv4', true, new RuleMessage('msg'), array(Constraint::DEFAULT_GROUP)),
                'ip',
            ),
            array(
                new Constraints\Ip(array('version' => Constraints\Ip::V4, 'message' => 'msg', 'groups' => array('ip'))),
                new ConstraintRule('ipv4', true, new RuleMessage('msg'), array('ip')),
                'ip',
            ),
            array(
                new Constraints\Ip(array('version' => Constraints\Ip::V4_NO_PRIV, 'message' => 'msgv4')),
                new ConstraintRule('ipv4', true, new RuleMessage('msgv4'), array(Constraint::DEFAULT_GROUP)),
                'ip',
            ),
            array(
                new Constraints\Ip(array('version' => Constraints\Ip::V4_NO_RES, 'message' => 'msgv4')),
                new ConstraintRule('ipv4', true, new RuleMessage('msgv4'), array(Constraint::DEFAULT_GROUP)),
                'ip',
            ),
            array(
                new Constraints\Ip(array('version' => Constraints\Ip::V4_ONLY_PUBLIC, 'message' => 'msgv4')),
                new ConstraintRule('ipv4', true, new RuleMessage('msgv4'), array(Constraint::DEFAULT_GROUP)),
                'ip',
            ),
            // Ip v6 only
            array(
                new Constraints\Ip(array('version' => Constraints\Ip::V6, 'message' => 'msgv6', 'groups' => array('ip'))),
                new ConstraintRule('ipv6', true, new RuleMessage('msgv6'), array('ip')),
                'ip',
            ),
            array(
                new Constraints\Ip(array('version' => Constraints\Ip::V6_NO_PRIV, 'message' => 'msgv6')),
                new ConstraintRule('ipv6', true, new RuleMessage('msgv6'), array(Constraint::DEFAULT_GROUP)),
                'ip',
            ),
            array(
                new Constraints\Ip(array('version' => Constraints\Ip::V6_NO_RES, 'message' => 'msgv6')),
                new ConstraintRule('ipv6', true, new RuleMessage('msgv6'), array(Constraint::DEFAULT_GROUP)),
                'ip',
            ),
            array(
                new Constraints\Ip(array('version' => Constraints\Ip::V6_ONLY_PUBLIC, 'message' => 'msgv6')),
                new ConstraintRule('ipv6', true, new RuleMessage('msgv6'), array(Constraint::DEFAULT_GROUP)),
                'ip',
            ),
            // Ip all only
            array(
                new Constraints\Ip(array('version' => Constraints\Ip::ALL, 'message' => 'msg')),
                new ConstraintRule('one_or_other', array('ipv4' => true, 'ipv6' => true), new RuleMessage('msg'), array(Constraint::DEFAULT_GROUP)),
                'ip',
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

    /**
     * @test
     */
    public function no_rules_if_disabled()
    {
        $mappingRule = new IpRule(false, false, true);
        $this->assertFalse($mappingRule->supports(new Constraints\Ip(), $this->form));
    }

    /**
     * @test
     * @dataProvider provide_unsupported_rule_configuration
     */
    public function no_rules_if_disabled_option(IpRule $mappingRule, Constraint $constraint)
    {
        $this->assertTrue($this->execute_supports($constraint));

        $mappingRule->resolve($constraint, $this->form, $this->ruleCollection);

        $this->assertCount(0, $this->ruleCollection);
    }

    public function provide_unsupported_rule_configuration()
    {
        return array(
            array(
                new IpRule(false, true, true),
                new Constraints\Ip(array('version' => Constraints\Ip::V4)),
            ),
            array(
                new IpRule(true, false, true),
                new Constraints\Ip(array('version' => Constraints\Ip::V6)),
            ),
            array(
                new IpRule(false, true, true),
                new Constraints\Ip(array('version' => Constraints\Ip::ALL)),
            ),
            array(
                new IpRule(true, false, true),
                new Constraints\Ip(array('version' => Constraints\Ip::ALL)),
            ),
            array(
                new IpRule(true, true, false),
                new Constraints\Ip(array('version' => Constraints\Ip::ALL)),
            ),
        );
    }
}
