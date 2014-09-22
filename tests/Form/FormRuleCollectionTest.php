<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormRuleCollectionTest extends \PHPUnit_Framework_TestCase
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
     * @var FormRuleCollection
     */
    protected $SUT;

    protected function setUp()
    {
        $this->form = $this->getMock('Symfony\Component\Form\FormInterface');
        $this->formView = $this->getMock('Symfony\Component\Form\FormView');

        $this->SUT = new FormRuleCollection($this->form, $this->formView);
    }

    /**
     * @test
     */
    public function it_should_return_the_form()
    {
        $this->assertEquals($this->form, $this->SUT->getForm());
    }

    /**
     * @test
     */
    public function it_should_return_the_form_view()
    {
        $this->assertEquals($this->formView, $this->SUT->getView());
    }

    /**
     * @test
     */
    public function it_should_return_self_if_root()
    {
        $this->assertTrue($this->SUT->isRoot());
        $this->assertEquals($this->SUT, $this->SUT->getRoot());
    }

    /**
     * @test
     */
    public function it_should_return_a_iterator()
    {
        $this->assertInstanceOf('Iterator', $this->SUT->getIterator());
    }

    /**
     * @test
     */
    public function it_should_add_child_rules()
    {
        $rules = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection');
        $this->SUT->add('name', $rules);

        $this->assertEquals(array('name' => $rules), $this->SUT->all());
    }

    /**
     * @test
     */
    public function it_should_add_merges_extra_rules()
    {
        $extraRules = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection');

        $rules = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection');
        $rules->expects($this->once())->method('addCollection')->with($extraRules);

        $this->SUT->add('name', $rules);
        $this->SUT->add('name', $extraRules);
    }

    /**
     * @test
     */
    public function it_should_set_child_rules()
    {
        $rules = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection');
        $this->SUT->set('name', $rules);

        $this->assertEquals(array('name' => $rules), $this->SUT->all());
    }

    /**
     * @test
     */
    public function it_should_override_child_rules_if_already_defined()
    {
        $newRules = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection');

        $rules = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection');
        $rules->expects($this->never())->method('addCollection');

        $this->SUT->set('name', $rules);
        $this->SUT->set('name', $newRules);

        $this->assertEquals(array('name' => $newRules), $this->SUT->all());
    }

    /**
     * @test
     */
    public function it_should_remove_child_rules()
    {
        $rules = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection');
        $this->SUT->set('name', $rules);

        $this->assertCount(1, $this->SUT);
        $this->assertEquals(array('name' => $rules), $this->SUT->all());

        $this->SUT->remove('name');
        $this->assertCount(0, $this->SUT);
        $this->assertEquals(array(), $this->SUT->all());
    }

    /**
     * @test
     */
    public function it_should_return_child_rules()
    {
        $rules = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection');
        $this->SUT->set('name', $rules);

        $this->assertEquals($rules, $this->SUT->get('name'));
    }

    /**
     * @test
     */
    public function it_should_be_able_to_add_other_collections()
    {
        $nameExtraRules = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection');
        $newRules = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection');

        $rules = $this->getMock('Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection');
        $rules->expects($this->once())->method('addCollection')->with($nameExtraRules);
        $this->SUT->set('name', $rules);

        $otherCollection = new FormRuleCollection($this->form, $this->formView);
        $otherCollection->set('name', $nameExtraRules);
        $otherCollection->set('name_new', $newRules);

        $this->SUT->addCollection($otherCollection);

        $this->assertEquals(array('name' => $rules, 'name_new' => $newRules), $this->SUT->all());
    }

    /**
     * @test
     */
    public function it_should_return_the_root()
    {
        $childCollection = new FormRuleCollection($this->form, $this->formView, $this->SUT);

        $this->assertFalse($childCollection->isRoot());
        $this->assertEquals($this->SUT, $childCollection->getRoot());
    }

    /**
     * @test
     */
    public function it_should_return_null_when_child_is_not_set()
    {
        $this->assertNull($this->SUT->get('nope'));
    }
}
