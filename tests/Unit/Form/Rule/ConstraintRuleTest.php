<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Unit\Form\Rule;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ConstraintRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function construct_should_set_default_values()
    {
        $rule = new ConstraintRule('name');

        $this->assertEquals('name', $rule->name);
        $this->assertNull($rule->options);
        $this->assertNull($rule->message);
        $this->assertEquals(array(Constraint::DEFAULT_GROUP), $rule->groups);
    }

    /**
     * @test
     */
    public function construct_should_set_values()
    {
        $name = 'my_rule';
        $options = false;
        $message = $this->getMockBuilder(RuleMessage::class)
            ->disableOriginalConstructor()->getMock();
        $groups = array('Yep', 'We', 'Have', 'groups');

        $rule = new ConstraintRule($name, $options, $message, $groups);

        $this->assertEquals($name, $rule->name);
        $this->assertEquals($options, $rule->options);
        $this->assertEquals($message, $rule->message);
        $this->assertEquals($groups, $rule->groups);
    }
}
