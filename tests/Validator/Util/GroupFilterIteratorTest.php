<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Validator\Util;

use Boekkooi\Bundle\JqueryValidationBundle\Validator\Util\GroupFilterIterator;
use Symfony\Component\Validator\Constraint;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Validator\Util\GroupFilterIterator
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class GroupFilterIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Boekkooi\Bundle\JqueryValidationBundle\Validator\GroupCollection | \PHPUnit_Framework_MockObject_MockObject
     */
    private $groupCollection;

    protected function setUp()
    {
        $this->groupCollection = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Validator\GroupCollection', null);
    }

    /**
     * @test
     */
    public function it_should_ignore_all_none_constraints()
    {
        $constraint = $this->given_a_constraint_with_groups();
        $this->groupCollection->add(Constraint::DEFAULT_GROUP);

        $groupFilterIterator = new GroupFilterIterator(
            new \ArrayIterator(array('', null, $constraint)),
            $this->groupCollection
        );

        $this->assertEquals(array($constraint), iterator_to_array($groupFilterIterator, false));
    }

    /**
     * @test
     */
    public function it_should_ignore_all_constraints_not_in_a_group()
    {
        $constraintOther1 = $this->given_a_constraint_with_groups();
        $constraintOther2 = $this->given_a_constraint_with_groups(array('other_group'));
        $constraintValid1 = $this->given_a_constraint_with_groups(array('my_group'));
        $constraintValid2 = $this->given_a_constraint_with_groups(array('other_group', 'my_group'));
        $this->groupCollection->add('my_group');

        $groupFilterIterator = new GroupFilterIterator(
            new \ArrayIterator(array($constraintOther1, $constraintValid1, $constraintOther2, $constraintValid2)),
            $this->groupCollection
        );

        $this->assertEquals(array($constraintValid1, $constraintValid2), iterator_to_array($groupFilterIterator, false));
    }

    /**
     * @param array $groups
     * @return \Symfony\Component\Validator\Constraint | \PHPUnit_Framework_MockObject_MockObject
     */
    protected function given_a_constraint_with_groups(array $groups = array(Constraint::DEFAULT_GROUP))
    {
        $constraint = $this->getMock('Symfony\Component\Validator\Constraint', null);
        $constraint->groups = $groups;

        return $constraint;
    }
}
