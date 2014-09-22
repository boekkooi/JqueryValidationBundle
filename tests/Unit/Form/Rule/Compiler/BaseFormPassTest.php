<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
abstract class BaseFormPassTest extends \PHPUnit_Framework_TestCase
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
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $constraintCollection;

    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Validator\GroupCollection | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $groupCollection;

    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface
     */
    protected $SUT;

    protected function setUp()
    {
        $this->form = $this->getMock('Symfony\Component\Form\FormInterface');
        $this->formView = $this->getMock('Symfony\Component\Form\FormView');

        $this->constraintCollection = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection', null);
        $this->groupCollection = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Validator\GroupCollection', null);

        $this->formRuleCollection = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection', null, array($this->form, $this->formView));
        $this->formContext = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Validator\FormContext', null, array($this->constraintCollection, $this->groupCollection));
    }

    /**
     * @test
     */
    public function it_should_implement_FormPassInterface()
    {
        $this->assertInstanceOf('Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface', $this->SUT);
    }

    protected function given_the_default_validation_group_is_used()
    {
        $this->groupCollection->add(Constraint::DEFAULT_GROUP);
    }

    protected function execute_process()
    {
        $this->SUT->process($this->formRuleCollection, $this->formContext);
    }
}
