<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Util;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_return_true_if_a_type_is_the_same()
    {
        $typeMock = $this->given_form_type_with_parents('text');

        $this->assertTrue(FormHelper::isType($typeMock, 'text'));
    }

    /**
     * @test
     */
    public function it_should_return_true_if_one_of_the_parents_is_the_same()
    {
        $this->assertTrue(
            FormHelper::isType(
                $this->given_form_type_with_parents('my', 'custom', 'type', 'text'),
                'text'
            )
        );

        $this->assertTrue(
            FormHelper::isType(
                $this->given_form_type_with_parents('my', 'custom', 'type', 'text'),
                'type'
            )
        );
    }

    /**
     * @test
     */
    public function it_should_return_false_if_the_type_is_not_found()
    {
        $this->assertFalse(
            FormHelper::isType(
                $this->given_form_type_with_parents('my'),
                'evil'
            )
        );

        $this->assertFalse(
            FormHelper::isType(
                $this->given_form_type_with_parents('my', 'custom', 'type', 'text'),
                'choice'
            )
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Form\ResolvedFormTypeInterface
     */
    protected function given_form_type_with_parents()
    {
        $types = array_reverse(func_get_args());

        $currentType = null;
        foreach ($types as $type) {
            $typeMock = $this->getMock('Symfony\Component\Form\ResolvedFormTypeInterface');
            $typeMock->expects($this->any())
                ->method('getParent')
                ->willReturn($currentType);
            $typeMock->expects($this->any())
                ->method('getName')
                ->willReturn($type);

            $currentType = $typeMock;
        }

        return $currentType;
    }

    /**
     * @test
     * @dataProvider provide_valid_form_names
     */
    public function it_should_return_the_form_full_name($view, $expected)
    {
        $this->assertEquals(
            FormHelper::getFormName($view),
            $expected
        );
    }

    public function provide_valid_form_names()
    {
        /** @var \Symfony\Component\Form\FormView $view */
        $view = $this->getMock('Symfony\Component\Form\FormView');
        $view->vars['full_name'] = 'form_name';

        return array(
            array('string_name', 'string_name'),
            array($view, 'form_name')
        );
    }

    /**
     * @test
     * @dataProvider provide_invalid_form_names
     */
    public function it_should_throw_a_exception_when_provided_with_a_invalid_type($view)
    {
        $this->setExpectedException('InvalidArgumentException');

        FormHelper::getFormName($view);
    }

    public function provide_invalid_form_names()
    {
        return array(
            array(false),
            array($this->getMock('Symfony\Component\Form\FormView'))
        );
    }
}
