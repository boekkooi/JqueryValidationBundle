<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
abstract class BaseProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Form\FormInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $form;

    /**
     * @var \Symfony\Component\Form\FormView | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $formView;

    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $constraintCollection;

    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $processorContext;

    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $formRuleContext;

    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface
     */
    protected $SUT;

    protected function setUp()
    {
        $this->form = $this->getMock('Symfony\Component\Form\FormInterface');
        $this->formView = $this->getMock('Symfony\Component\Form\FormView');
        $this->constraintCollection = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection', null);

        $this->processorContext = $this->getMock(
            'Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext',
            null,
            array(
                $this->formView,
                $this->form,
                $this->constraintCollection
            )
        );

        $this->formRuleContext = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder', null);
    }

    /**
     * @test
     */
    public function it_should_implement_FormPassInterface()
    {
        $this->assertInstanceOf('Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface', $this->SUT);
    }

    protected function execute_process()
    {
        $this->SUT->process($this->processorContext, $this->formRuleContext);
    }
}
