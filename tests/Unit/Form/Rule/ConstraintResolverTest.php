<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintResolver;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintResolver
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ConstraintResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Form\FormInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $form;

    /**
     * @var \Symfony\Component\Validator\Constraint | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $constraint;

    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $constraintCollection;

    /**
     * @var ConstraintResolver
     */
    protected $SUT;

    protected function setUp()
    {
        $this->constraint = $this->getMock('Symfony\Component\Validator\Constraint');
        $this->constraintCollection = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection', null);
        $this->constraintCollection->add($this->constraint);
        $this->form = $this->getMock('Symfony\Component\Form\FormInterface');

        $this->SUT = new ConstraintResolver();
    }

    /**
     * @test
     */
    public function it_should_not_call_mappers_that_are_not_supporting_a_constraint()
    {
        $mapper = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface');
        $mapper->expects($this->exactly(1))->method('supports')->with($this->constraint, $this->form)->willReturn(false);
        $mapper->expects($this->never())->method('resolve');

        $this->SUT->addMapper($mapper);
        $this->SUT->resolve($this->constraintCollection, $this->form);
    }

    /**
     * @test
     */
    public function it_should_call_resolve_if_a_constraint_is_supported()
    {
        $mapper = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface');
        $mapper->expects($this->exactly(2))->method('supports')->with($this->constraint, $this->form)->willReturn(true);
        $mapper->expects($this->exactly(2))->method('resolve');

        $this->constraintCollection->add($this->constraint);

        $this->SUT->addMapper($mapper);
        $this->SUT->resolve($this->constraintCollection, $this->form);
    }
}
