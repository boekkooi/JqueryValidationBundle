<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\FileRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\FileRule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FileRuleTest extends BaseConstraintMapperTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new FileRule(true);
    }

    public function provide_supported_constraints()
    {
        return array(
            array(new Constraints\File(array('mimeTypes' => array('abc')))),
        );
    }

    public function provide_constraint_rule_expectation()
    {
        return array(
            array(
                new Constraints\File(array('mimeTypes' => array('abc'), 'mimeTypesMessage' => 'msg', 'groups' => array('group'))),
                new Rule('accept', 'abc', new RuleMessage('msg', array('{{ types }}' => 'abc')), array('group'))
            ),
            array(
                new Constraints\File(array('mimeTypes' => array('abc', 'xyz'), 'mimeTypesMessage' => 'msg')),
                new Rule('accept', 'abc,xyz', new RuleMessage('msg', array('{{ types }}' => 'abc, xyz')), array(Constraint::DEFAULT_GROUP))
            )
        );
    }

    public function provide_unsupported_constraints()
    {
        return array(
            array(new Constraints\File()),
            array(new Constraints\File(array('mimeTypes' => array()))),
            array(new Constraints\NotBlank()),
            array(new Constraints\NotNull())
        );
    }

    /**
     * @test
     * @dataProvider provide_constraint_rule_expectation
     */
    public function no_rules_if_disabled(Constraint $constraint)
    {
        $mappingRule = new FileRule(false);
        $this->assertFalse($mappingRule->supports($constraint, $this->form));
    }
}
