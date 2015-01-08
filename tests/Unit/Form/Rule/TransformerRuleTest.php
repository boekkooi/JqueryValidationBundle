<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\TransformerRule;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class TransformerRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function construct_should_set_default_values()
    {
        $rule = new TransformerRule('name');

        $this->assertEquals('name', $rule->name);
        $this->assertNull($rule->options);
        $this->assertNull($rule->message);
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

        $rule = new TransformerRule($name, $options, $message, $groups);

        $this->assertEquals($name, $rule->name);
        $this->assertEquals($options, $rule->options);
        $this->assertEquals($message, $rule->message);
    }
}
