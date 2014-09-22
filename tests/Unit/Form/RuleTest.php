<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Symfony\Component\Validator\Constraint;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function construct_should_set_default_values()
    {
        $rule = new Rule('name');

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
        $message = $this->getMockBuilder('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage')
            ->disableOriginalConstructor()->getMock();
        $groups = array('Yep', 'We', 'Have', 'groups');

        $rule = new Rule($name, $options, $message, $groups);

        $this->assertEquals($name, $rule->name);
        $this->assertEquals($options, $rule->options);
        $this->assertEquals($message, $rule->message);
        $this->assertEquals($groups, $rule->groups);
    }
}
