<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
abstract class BaseConstraintMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $ruleCollection;

    /**
     * @var \Symfony\Component\Form\FormInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $form;

    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface
     */
    protected $SUT;

    protected function setUp()
    {
        $this->ruleCollection = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection', null);
        $this->form = $this->getMock('Symfony\Component\Form\FormInterface');
    }

    protected function setUpBaseTest()
    {
    }

    public function execute_supports(Constraint $constraint)
    {
        return $this->SUT->supports($constraint, $this->form);
    }

    public function execute_resolve(Constraint $constraint)
    {
        $this->SUT->resolve($constraint, $this->form, $this->ruleCollection);
    }

    /**
     * @test
     * @dataProvider provide_supported_constraints
     */
    public function it_should_support_constraint(Constraint $constraint)
    {
        $this->setUpBaseTest();

        $this->assertTrue($this->execute_supports($constraint));
    }

    abstract public function provide_supported_constraints();

    /**
     * @test
     * @dataProvider provide_constraint_rule_expectation
     */
    public function it_should_add_a_rule_for(Constraint $constraint, Rule $rule, $ruleName = null)
    {
        $ruleName = $ruleName !== null ? $ruleName : $rule->name;

        $this->setUpBaseTest();

        $this->execute_resolve($constraint);

        $this->assertCount(1, $this->ruleCollection);
        $this->assertNotNull($this->ruleCollection->get($ruleName));
        $this->assertSameRule($rule, $this->ruleCollection->get($ruleName));
    }

    abstract public function provide_constraint_rule_expectation();

    /**
     * @test
     * @dataProvider provide_unsupported_constraints
     */
    public function it_should_not_support_constraint(Constraint $constraint)
    {
        $this->setUpBaseTest();

        $this->assertFalse($this->execute_supports($constraint));
    }

    /**
     * @test
     * @dataProvider provide_unsupported_constraints
     */
    public function it_should_throw_a_exception_when_resolving_a_unsupported_constraint(Constraint $constraint)
    {
        $this->setUpBaseTest();

        $this->setExpectedException('LogicException');

        $this->execute_resolve($constraint);
    }

    abstract public function provide_unsupported_constraints();

    protected function assertSameRule(Rule $expectedRule, Rule $rule)
    {
        $this->assertEquals($expectedRule->name, $rule->name, 'Invalid rule name');
        $this->assertEquals($expectedRule->groups, $rule->groups, 'Invalid rule groups');
        $this->assertEquals($expectedRule->options, $rule->options, 'Invalid rule options');

        if ($expectedRule->message === null) {
            $this->assertNull($rule->message);
        } else {
            $this->assertInstanceOf('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage', $rule->message);
            $this->assertEquals($expectedRule->message->message, $rule->message->message);
            $this->assertEquals($expectedRule->message->parameters, $rule->message->parameters);
            $this->assertEquals($expectedRule->message->plural, $rule->message->plural);
        }
    }
}
