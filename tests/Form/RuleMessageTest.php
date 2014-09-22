<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RuleMessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function construct_should_set_default_values()
    {
        $message = new RuleMessage('msg');

        $this->assertEquals('msg', $message->message);
        $this->assertEquals(array(), $message->parameters);
        $this->assertNull($message->plural);
    }

    /**
     * @test
     */
    public function construct_should_set_values()
    {
        $parameters = array('{{ param }}' => '123');
        $plural = 123;

        $message = new RuleMessage('msg', $parameters, $plural);

        $this->assertEquals('msg', $message->message);
        $this->assertEquals($parameters, $message->parameters);
        $this->assertEquals($plural, $message->plural);
    }
}
