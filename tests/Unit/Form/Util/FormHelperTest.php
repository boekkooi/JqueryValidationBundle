<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Util;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;
use Symfony\Component\Validator\Constraint;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function isType_should_return_true_if_a_type_is_the_same()
    {
        $typeMock = $this->given_form_type_with_parents('Symfony\Component\Form\Extension\Core\Type\TextType');

        self::assertTrue(FormHelper::isType($typeMock, 'Symfony\Component\Form\Extension\Core\Type\TextType'));
    }

    /**
     * @test
     */
    public function isType_should_return_true_if_one_of_the_parents_is_the_same()
    {
        if (
            !method_exists('Symfony\Component\Form\ResolvedFormTypeInterface', 'getName') ||
            method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')
        ) {
            self::markTestSkipped('Symfony 2 without 3 compatibility only!');
        }

        self::assertTrue(
            FormHelper::isType(
                $this->given_form_type_with_parents('my', 'custom', 'type', 'text'),
                'text'
            )
        );

        self::assertTrue(
            FormHelper::isType(
                $this->given_form_type_with_parents('my', 'custom', 'type', 'text'),
                'text'
            )
        );
    }

    /**
     * @test
     */
    public function isType_should_return_false_if_the_type_is_not_found()
    {
        if (
            !method_exists('Symfony\Component\Form\ResolvedFormTypeInterface', 'getName') ||
            method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')
        ) {
            self::markTestSkipped('Symfony 2 without 3 compatibility only!');
        }

        self::assertFalse(
            FormHelper::isType(
                $this->given_form_type_with_parents('my'),
                'evil'
            )
        );

        self::assertFalse(
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
            $typeMock->expects(self::any())
                ->method('getParent')
                ->willReturn($currentType);
            if (FormHelper::isSymfony3Compatible()) {
                $innerType = new $type();
                $typeMock->expects(self::any())
                    ->method('getInnerType')
                    ->willReturn($innerType);

                if (FormHelper::isSymfony2Compatible()) {
                    $typeMock->expects(self::any())
                        ->method('getName')
                        ->willReturn($innerType->getName());
                }
            } elseif (FormHelper::isSymfony2Compatible()) {
                $typeMock->expects(self::any())
                    ->method('getName')
                    ->willReturn($type);
            }

            $currentType = $typeMock;
        }

        return $currentType;
    }

    /**
     * @test
     * @dataProvider provide_valid_form_names
     */
    public function getFormName_should_return_the_form_full_name($view, $expected)
    {
        self::assertEquals(
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
            array($view, 'form_name'),
        );
    }

    /**
     * @test
     * @dataProvider provide_invalid_form_names
     */
    public function getFormName_should_throw_a_exception_when_provided_with_a_invalid_type($view)
    {
        $this->setExpectedException('InvalidArgumentException');

        FormHelper::getFormName($view);
    }

    public function provide_invalid_form_names()
    {
        return array(
            array(false),
            array($this->getMock('Symfony\Component\Form\FormView')),
        );
    }

    /**
     * @test
     */
    public function getValidationGroups_should_return_the_jquery_validation_groups_option()
    {
        $formConfig = $this->getMock('Symfony\Component\Form\FormConfigInterface');
        $formConfig->expects(self::any())->method('hasOption')->with('jquery_validation_groups')->willReturn(true);
        $formConfig->expects(self::any())->method('getOption')->with('jquery_validation_groups')->willReturn('my_group');

        /** @var \Symfony\Component\Form\FormInterface|\PHPUnit_Framework_MockObject_MockObject $form */
        $form = $this->getMock('Symfony\Component\Form\FormInterface');
        $form->expects(self::any())->method('getConfig')->willReturn($formConfig);

        self::assertEquals(
            array('my_group'),
            FormHelper::getValidationGroups($form)
        );
    }

    /**
     * @test
     */
    public function getValidationGroups_should_return_validation_groups_option_when_query_validation_groups_is_not_set()
    {
        $formConfig = $this->getMock('Symfony\Component\Form\FormConfigInterface');
        $formConfig->expects(self::any())->method('hasOption')->with('jquery_validation_groups')->willReturn(false);
        $formConfig->expects(self::any())->method('getOption')->with('validation_groups')->willReturn('my_val_group');

        /** @var \Symfony\Component\Form\FormInterface|\PHPUnit_Framework_MockObject_MockObject $form */
        $form = $this->getMock('Symfony\Component\Form\FormInterface');
        $form->expects(self::any())->method('getConfig')->willReturn($formConfig);

        self::assertEquals(
            array('my_val_group'),
            FormHelper::getValidationGroups($form)
        );
    }

    /**
     * @test
     * @dataProvider provide_getValidationGroups_valid_return_values
     */
    public function getValidationGroups_should_return($value, $return)
    {
        $formConfig = $this->getMock('Symfony\Component\Form\FormConfigInterface');
        $formConfig->expects(self::any())->method('hasOption')->with('jquery_validation_groups')->willReturn(false);
        $formConfig->expects(self::any())->method('getOption')->with('validation_groups')->willReturn($value);

        /** @var \Symfony\Component\Form\FormInterface|\PHPUnit_Framework_MockObject_MockObject $form */
        $form = $this->getMock('Symfony\Component\Form\FormInterface');
        $form->expects(self::any())->method('getConfig')->willReturn($formConfig);

        self::assertEquals(
            $return,
            FormHelper::getValidationGroups($form)
        );
    }

    public function provide_getValidationGroups_valid_return_values()
    {
        return array(
            array(null, array(Constraint::DEFAULT_GROUP)),
            array(false, array()),
            array(
                array('my', 'valid', 'groups'),
                array('my', 'valid', 'groups')
            ),
        );
    }

    /**
     * @test
     */
    public function getViewRoot_should_return_the_root_view()
    {
        /** @var \Symfony\Component\Form\FormView|\PHPUnit_Framework_MockObject_MockObject $rootView */
        $rootView = $this->getMock('Symfony\Component\Form\FormView');
        self::assertEquals(
            $rootView,
            FormHelper::getViewRoot($rootView)
        );

        /** @var \Symfony\Component\Form\FormView|\PHPUnit_Framework_MockObject_MockObject $formView */
        $formView = $this->getMock('Symfony\Component\Form\FormView');
        $formView->parent = $rootView;
        self::assertEquals(
            $rootView,
            FormHelper::getViewRoot($formView)
        );
    }
}
