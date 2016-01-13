<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Util;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ResolvedFormTypeInterface;
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
        $typeMock = $this->given_form_type_with_parents(TextType::class);

        self::assertTrue(FormHelper::isType($typeMock, TextType::class));
    }

    /**
     * @test
     */
    public function isType_should_return_true_if_one_of_the_parents_is_the_same()
    {
        if (
            !method_exists(ResolvedFormTypeInterface::class, 'getName') ||
            method_exists(AbstractType::class, 'getBlockPrefix')
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
            !method_exists(ResolvedFormTypeInterface::class, 'getName') ||
            method_exists(AbstractType::class, 'getBlockPrefix')
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
            $typeMock = $this->getMock(ResolvedFormTypeInterface::class);
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
        /** @var FormView $view */
        $view = $this->getMock(FormView::class);
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
            array($this->getMock(FormView::class)),
        );
    }

    /**
     * @test
     */
    public function getValidationGroups_should_return_the_jquery_validation_groups_option()
    {
        $formConfig = $this->getMock(FormConfigInterface::class);
        $formConfig->expects(self::any())->method('hasOption')->with('jquery_validation_groups')->willReturn(true);
        $formConfig->expects(self::any())->method('getOption')->with('jquery_validation_groups')->willReturn('my_group');

        /** @var \Symfony\Component\Form\FormInterface|\PHPUnit_Framework_MockObject_MockObject $form */
        $form = $this->getMock(FormInterface::class);
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
        $formConfig = $this->getMock(FormConfigInterface::class);
        $formConfig->expects(self::any())->method('hasOption')->with('jquery_validation_groups')->willReturn(false);
        $formConfig->expects(self::any())->method('getOption')->with('validation_groups')->willReturn('my_val_group');

        /** @var \Symfony\Component\Form\FormInterface|\PHPUnit_Framework_MockObject_MockObject $form */
        $form = $this->getMock(FormInterface::class);
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
        $formConfig = $this->getMock(FormConfigInterface::class);
        $formConfig->expects(self::any())->method('hasOption')->with('jquery_validation_groups')->willReturn(false);
        $formConfig->expects(self::any())->method('getOption')->with('validation_groups')->willReturn($value);

        /** @var \Symfony\Component\Form\FormInterface|\PHPUnit_Framework_MockObject_MockObject $form */
        $form = $this->getMock(FormInterface::class);
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
        $rootView = $this->getMock(FormView::class);
        self::assertEquals(
            $rootView,
            FormHelper::getViewRoot($rootView)
        );

        /** @var \Symfony\Component\Form\FormView|\PHPUnit_Framework_MockObject_MockObject $formView */
        $formView = $this->getMock(FormView::class);
        $formView->parent = $rootView;
        self::assertEquals(
            $rootView,
            FormHelper::getViewRoot($formView)
        );
    }
}
