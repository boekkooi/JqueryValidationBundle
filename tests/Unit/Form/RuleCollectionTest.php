<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Unit\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RuleCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RuleCollection
     */
    private $SUT;

    protected function setUp()
    {
        $this->SUT = new RuleCollection();
    }

    /**
     * @test
     */
    public function add_should_throw_a_logic_exception()
    {
        $this->setExpectedException('LogicException');

        $this->SUT->add($this->create_a_rule_mock());
    }

    /**
     * @test
     */
    public function set_should_set_a_rule()
    {
        $rule = $this->create_a_rule_mock();

        $this->SUT->set('name', $rule);

        $this->assertEquals(array('name' => $rule), $this->SUT->toArray());
    }

    /**
     * @test
     */
    public function it_should_allow_adding_collections()
    {
        $rule = $this->create_a_rule_mock();
        $rule1 = $this->create_a_rule_mock();
        $rule2 = $this->create_a_rule_mock();

        $collection = new RuleCollection();
        $collection->set('rule1', $rule1);
        $collection->set('rule2', $rule2);

        $this->SUT->set('rule', $rule);
        $this->SUT->addCollection($collection);

        $this->assertEquals(
            array(
                'rule' => $rule,
                'rule1' => $rule1,
                'rule2' => $rule2,
            ),
            $this->SUT->toArray()
        );
    }

    /**
     * @test
     * @dataProvider provide_invalid_set_arguments
     */
    public function set_should_only_allow_rule_instances($value)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->SUT->set('rule', $value);
    }

    public function provide_invalid_set_arguments()
    {
        return array(
            array(''),
            array(new \stdClass()),
            array(null),
            array(1),
            array(true),
        );
    }

    private function create_a_rule_mock()
    {
        return $this->getMockBuilder(Rule::class)
            ->disableOriginalConstructor()->getMock();
    }
}
