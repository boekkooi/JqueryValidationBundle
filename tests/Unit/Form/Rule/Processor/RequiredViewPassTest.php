<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor\RequiredViewPass;
use Symfony\Component\Validator\Constraint;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor\RequiredViewPass
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RequiredViewPassTest extends BaseProcessorTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new RequiredViewPass();
    }

    /**
     * @test
     * @dataProvider provide_required_constraint_classes
     */
    public function it_should_set_required_if_a_required_constraint_is_found()
    {
        $this->given_the_form_view_attr_required_is(false);

        foreach (func_get_args() as $constraintClass) {
            $this->constraintCollection->add(new $constraintClass(array('groups' => Constraint::DEFAULT_GROUP)));
        }

        $this->execute_process();

        $this->assertTrue($this->formView->vars['required']);
    }

    /**
     * @test
     * @dataProvider provide_not_required_constraint_classes
     */
    public function it_should_set_not_required_if_a_required_constraint_is_not_found()
    {
        $this->given_the_form_view_attr_required_is(true);

        foreach (func_get_args() as $constraintClass) {
            $this->constraintCollection->add(new $constraintClass(array('groups' => Constraint::DEFAULT_GROUP)));
        }

        $this->execute_process();

        $this->assertFalse($this->formView->vars['required']);
    }

    /**
     * @test
     */
    public function is_should_do_nothing_when_required_is_not_set()
    {
        $this->execute_process();

        $this->assertFalse(isset($this->formView->vars['required']));
    }

    public function given_the_form_view_attr_required_is($value)
    {
        $this->formView->vars['required'] = $value;
    }

    public function provide_required_constraint_classes()
    {
        return array(
            array('Symfony\Component\Validator\Constraints\NotNull'),
            array('Symfony\Component\Validator\Constraints\NotBlank'),
            array('Symfony\Component\Validator\Constraints\Required'),

            array( 'Symfony\Component\Validator\Constraints\Currency', 'Symfony\Component\Validator\Constraints\NotNull'),
            array( 'Symfony\Component\Validator\Constraints\Time', 'Symfony\Component\Validator\Constraints\NotBlank'),
            array( 'Symfony\Component\Validator\Constraints\Locale', 'Symfony\Component\Validator\Constraints\Required'),
        );
    }

    public function provide_not_required_constraint_classes()
    {
        return array(
            array(
                'Symfony\Component\Validator\Constraints\Currency',
                'Symfony\Component\Validator\Constraints\Time',
                'Symfony\Component\Validator\Constraints\Locale'
            ),
            array(
                'Symfony\Component\Validator\Constraints\Blank'
            ),
            array()
        );
    }
}
