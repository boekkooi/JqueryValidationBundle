<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Validator;

use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ConstraintCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConstraintCollection
     */
    private $SUT;

    /**
     * @var \Symfony\Component\Validator\Constraint | \PHPUnit_Framework_MockObject_MockObject
     */
    private $constraint;

    protected function setUp()
    {
        $this->constraint = $this->getMock('Symfony\Component\Validator\Constraint');
        $this->SUT = new ConstraintCollection();
    }

    /**
     * @test
     */
    public function it_should_add_constraint_instance()
    {
        $this->SUT->add($this->constraint);

        $this->assertEquals(array($this->constraint), $this->SUT->toArray());
    }

    /**
     * @test
     */
    public function it_should_allow_adding_collections()
    {
        $c1 = $this->getMock('Symfony\Component\Validator\Constraint');
        $c2 = $this->getMock('Symfony\Component\Validator\Constraint');

        $collection = new ConstraintCollection();
        $collection->add($c1);
        $collection->add($c2);

        $this->SUT->add($this->constraint);
        $this->SUT->addCollection($collection);

        $this->assertEquals(array($this->constraint, $c1, $c2), $this->SUT->toArray());
    }

    /**
     * @test
     */
    public function it_should_set_constraint_instance()
    {
        $this->SUT->set(2, $this->constraint);

        $this->assertEquals(array(2 => $this->constraint), $this->SUT->toArray());
    }

    /**
     * @test
     * @dataProvider provide_invalid_arguments
     */
    public function it_should_only_allow_constraint_instances($value)
    {
        $this->setExpectedException('\InvalidArgumentException');

        $this->SUT->add($value);
    }

    public function provide_invalid_arguments()
    {
        return array(
            array(''),
            array(new \stdClass()),
            array(null),
            array(1),
            array(true)
        );
    }
}
