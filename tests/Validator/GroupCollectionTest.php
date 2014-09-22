<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Validator;

use Boekkooi\Bundle\JqueryValidationBundle\Validator\GroupCollection;
use Symfony\Component\Validator\Constraint;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Validator\GroupCollection
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class GroupCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GroupCollection
     */
    private $SUT;

    protected function setUp()
    {
        $this->SUT = new GroupCollection();
    }

    /**
     * @test
     * @dataProvider provide_valid_groups_for_add
     */
    public function it_should_add_group($group, $expected = null)
    {
        if ($expected === null) {
            $expected = array($group);
        }

        $this->SUT->add($group);

        $this->assertEquals($expected, $this->SUT->toArray());
    }

    public function provide_valid_groups_for_add()
    {
        return array_merge(
            $this->provide_valid_groups_for_set(),
            array(
                array(new \ArrayIterator(array('iterator_group')), array('iterator_group')),
                array(array($this, 'provide_valid_groups_for_add'), array(array($this, 'provide_valid_groups_for_add'))),
                array(array('my_group',1, false), array('my_group',1, false))
            )
        );
    }

    /**
     * @test
     * @dataProvider provide_invalid_groups_for_add
     */
    public function it_should_throw_a_exception_if_add_is_given_a_invalid_group($group)
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->SUT->add($group);
    }

    public function provide_invalid_groups_for_add()
    {
        return array(
            array(null),
            array(new \stdClass()),
            array(0.1),
            array(array(new \stdClass()))
        );
    }

    /**
     * @test
     * @dataProvider provide_valid_groups_for_set
     */
    public function it_should_set_a_group($group)
    {
        $this->SUT->set(1, $group);

        $this->assertEquals(array(1 => $group), $this->SUT->toArray());
    }

    public function provide_valid_groups_for_set()
    {
        return array(
            array(Constraint::DEFAULT_GROUP),
            array(false),
            array('my_group'),
            array(array($this, 'provide_valid_groups_for_add')),
            array(1)
        );
    }

    /**
     * @test
     * @dataProvider provide_invalid_groups_for_set
     */
    public function it_should_throw_a_exception_if_set_is_given_a_invalid_group($group)
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->SUT->set(1, $group);
    }

    public function provide_invalid_groups_for_set()
    {
        return array_merge(
            $this->provide_invalid_groups_for_add(),
            array(array(array(false)))
        );
    }
}
