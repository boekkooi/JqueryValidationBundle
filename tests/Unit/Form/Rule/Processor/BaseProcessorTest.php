<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

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
     * @var \Symfony\Component\Form\FormConfigInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $formConfig;

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
        $this->form = $this->getMock(FormInterface::class);
        $this->formView = $this->getMock(FormView::class);
        $this->constraintCollection = $this->getMock(ConstraintCollection::class, null);

        $this->processorContext = $this->getMock(
            FormRuleProcessorContext::class,
            null,
            array(
                $this->formView,
                $this->form,
                $this->constraintCollection,
            )
        );

        $this->formRuleContext = $this->getMock(FormRuleContextBuilder::class);

        $this->setUpFormConfig();
    }

    protected function setUpFormConfig()
    {
        $this->formConfig = $this->getMock(FormConfigInterface::class);
        $this->form->expects($this->any())
            ->method('getConfig')
            ->willReturn($this->formConfig);
    }

    /**
     * @test
     */
    public function it_should_implement_FormPassInterface()
    {
        $this->assertInstanceOf(FormRuleProcessorInterface::class, $this->SUT);
    }

    protected function execute_process()
    {
        $this->SUT->process($this->processorContext, $this->formRuleContext);
    }
}
