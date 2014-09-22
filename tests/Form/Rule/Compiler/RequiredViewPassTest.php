<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler\RequiredViewPass;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RequiredViewPassTest extends \PHPUnit_Framework_TestCase
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
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $formRuleCollection;

    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Validator\FormContext | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $formContext;

    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface
     */
    protected $SUT;

    protected function setUp()
    {
        $this->form = $this->getMock('Symfony\Component\Form\FormInterface');
        $this->formView = $this->getMock('Symfony\Component\Form\FormView');

        $this->formRuleCollection = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection', array(), array($this->form, $this->formView));
        $this->formContext = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Validator\FormContext');


        $this->SUT = new RequiredViewPass();
    }

    /**
     * @test
     */
    public function it_should_implement_FormPassInterface()
    {
        $this->assertInstanceOf('Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface', $this->SUT);
    }

    protected function execute_process()
    {
        $this->SUT->process($this->formRuleCollection, $this->formContext);
    }
}
 