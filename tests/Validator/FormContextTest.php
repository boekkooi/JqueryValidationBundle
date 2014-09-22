<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Validator;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\FormContext;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\GroupCollection;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection | \PHPUnit_Framework_MockObject_MockObject
     */
    private $constraintCollection;

    /**
     * @var GroupCollection
     */
    private $groupCollection;

    /**
     * @var FormContext
     */
    private $SUT;

    protected function setUp()
    {
        $this->constraintCollection = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection');
        $this->groupCollection = new GroupCollection();
        $this->SUT = new FormContext($this->constraintCollection, $this->groupCollection);
    }

    /**
     * @test
     */
    public function it_should_be_traversable()
    {
        $this->assertInstanceOf('Traversable', $this->SUT);
    }

    /**
     * @test
     */
    public function it_should_be_disabled_when_a_group_exists_that_is_false()
    {
        $this->given_group_contain_false();

        $this->assertTrue($this->SUT->isDisabled());
    }

    /**
     * @test
     */
    public function is_should_not_be_disabled_with_a_default_group()
    {
        $this->assertFalse($this->SUT->isDisabled());
    }

    /**
     * @test
     */
    public function it_should_return_the_group_collection()
    {
        $this->assertEquals($this->groupCollection, $this->SUT->getGroups());
    }

    /**
     * @test
     */
    public function it_should_return_the_constraint_collection()
    {
        $this->assertEquals($this->constraintCollection, $this->SUT->getConstraints());
    }

    /**
     * @test
     */
    public function it_should_return_a_empty_iterator_if_disabled()
    {
        $this->given_group_contain_false();

        $iterator = $this->SUT->getIterator();

        $this->assertInstanceOf('EmptyIterator', $iterator);
    }

    /**
     * @test
     */
    public function it_should_return_a_empty_iterator_there_are_no_groups()
    {
        $iterator = $this->SUT->getIterator();

        $this->assertInstanceOf('EmptyIterator', $iterator);
    }

    /**
     * @test
     */
    public function it_should_return_a_group_filter_iterator_if_there_are_any_groups()
    {
        $this->groupCollection->add(Constraint::DEFAULT_GROUP);
        $this->constraintCollection->expects($this->any())->method('getIterator')->willReturn($this->getMock('Iterator'));

        $iterator = $this->SUT->getIterator();

        $this->assertInstanceOf('Boekkooi\Bundle\JqueryValidationBundle\Validator\Util\GroupFilterIterator', $iterator);
        $this->assertAttributeEquals($this->groupCollection, 'groups', $iterator);
    }

    private function given_group_contain_false()
    {
        $this->groupCollection->add(false);
    }
}
