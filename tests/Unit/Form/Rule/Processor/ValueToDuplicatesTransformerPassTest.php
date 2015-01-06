<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Unit\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor\ValueToDuplicatesTransformerPass;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\TransformerRule;
use Symfony\Component\Form\Extension\Core\DataTransformer\ValueToDuplicatesTransformer;
use Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler\BaseProcessorTest;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor\ValueToDuplicatesTransformerPass
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ValueToDuplicatesTransformerPassTest extends BaseProcessorTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new ValueToDuplicatesTransformerPass();
    }

    /**
     * @test
     */
    public function it_should_set_equal_rule()
    {
        $this->prepare_form_config_compound();
        $this->prepare_form_config_view_transformers(array(
            new ValueToDuplicatesTransformer(array('first', 'second'))
        ));

        $this->formView->vars['full_name'] = 'pwd';

        /** @var \Symfony\Component\Form\FormView $firstFormView */
        $firstFormView = $this->getMock('Symfony\Component\Form\FormView');
        $firstFormView->vars['full_name'] = 'pwd_first';
        $firstFormView->vars['attr']['id'] = 'pwdFirst';
        $this->formView->children['first'] = $firstFormView;

        /** @var \Symfony\Component\Form\FormView $secondFormView */
        $secondFormView = $this->getMock('Symfony\Component\Form\FormView');
        $secondFormView->vars['full_name'] = 'pwd_second';
        $this->formView->children['second'] = $secondFormView;

        $formRuleCollection = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection');
        $this->formRuleContext->expects($this->atLeastOnce())
            ->method('get')
            ->with($this->formView)
            ->willReturn($formRuleCollection);
        $this->formRuleContext->expects($this->once())
            ->method('remove')
            ->with($this->formView);

        $secondCollection = new RuleCollection();
        $secondCollection->set(
            'equalTo',
            new TransformerRule(
                'equalTo',
                '#pwdFirst',
                null
            )
        );

        $self = $this;
        $this->formRuleContext->expects($this->atLeast(2))
            ->method('add')
            ->willReturnCallback(function($view, $collection) use ($self, $firstFormView, $formRuleCollection, $secondFormView, $secondCollection) {
                if ($view === $firstFormView) {
                    $self->assertEquals($formRuleCollection, $collection, 'Primary rules');
                } elseif ($view === $secondFormView) {
                    $self->assertEquals($secondCollection, $collection, 'Second rules');
                } else {
                    $self->fail('unexpected call');
                }
            });

        $this->execute_process();
    }

    /**
     * @test
     */
    public function it_should_ignore_a_none_compound_form()
    {
        $this->prepare_form_config_compound(false);

        $this->expect_no_form_rule_context_interaction();

        $this->execute_process();
    }

    /**
     * @test
     */
    public function it_should_ignore_a_form_without_the_transformer()
    {
        $this->prepare_form_config_compound();
        $this->prepare_form_config_view_transformers(array(
            $this->getMock('Symfony\Component\Form\DataTransformerInterface')
        ));

        $this->expect_no_form_rule_context_interaction();

        $this->execute_process();
    }

    /**
     * @test
     */
    public function it_should_ignore_a_form_with_a_transformer_without_keys()
    {
        $this->prepare_form_config_compound();
        $this->prepare_form_config_view_transformers(array(
            new ValueToDuplicatesTransformer(array())
        ));

        $this->expect_no_form_rule_context_interaction();

        $this->execute_process();
    }

    /**
     * @param bool $isCompound
     */
    private function prepare_form_config_compound($isCompound = true)
    {
        $this->formConfig->expects($this->any())
            ->method('getCompound')
            ->willReturn($isCompound);
    }

    /**
     * @param \Symfony\Component\Form\DataTransformerInterface[] $transformers
     */
    private function prepare_form_config_view_transformers($transformers = array())
    {
        $this->formConfig->expects($this->any())
            ->method('getViewTransformers')
            ->willReturn($transformers);
    }

    private function expect_no_form_rule_context_interaction()
    {
        $this->formRuleContext->expects($this->never())->method('add');
        $this->formRuleContext->expects($this->never())->method('get');
        $this->formRuleContext->expects($this->never())->method('remove');
    }
}