<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MinLengthRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\MinLengthRule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class MinLengthRuleTest extends BaseConstraintMapperTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new MinLengthRule();
    }

    protected function setUpBaseTest()
    {
        $this->given_form_is_of_type('Symfony\Component\Form\Extension\Core\Type\TextType');
    }

    /**
     * @test
     */
    public function it_should_not_support_length_constraint_for_choice_form_type()
    {
        $this->given_form_is_of_type('Symfony\Component\Form\Extension\Core\Type\ChoiceType');

        self::assertFalse($this->execute_supports(new Constraints\Length(array('min' => 1))));
    }

    public function provide_supported_constraints()
    {
        return array(
            array(new Constraints\Choice(array('choices' => array(), 'min' => 1))),
            array(new Constraints\Length(array('min' => 1))),
        );
    }

    public function provide_constraint_rule_expectation()
    {
        return array(
            array(
                new Constraints\Length(array('min' => 3, 'minMessage' => 'Yo')),
                new ConstraintRule('minlength', 3, new RuleMessage('Yo', array('{{ limit }}' => 3), 3), array(Constraint::DEFAULT_GROUP)),
            ),
            array(
                new Constraints\Choice(array('min' => 2, 'minMessage' => 'Choosy', 'choices' => array())),
                new ConstraintRule('minlength', 2, new RuleMessage('Choosy', array('{{ limit }}' => 2), 2), array(Constraint::DEFAULT_GROUP)),
            ),
        );
    }

    public function provide_unsupported_constraints()
    {
        return array(
            array(new Constraints\Length(array('max' => 1))),
            array(new Constraints\Length(array('min' => 1, 'max' => 1))),

            array(new Constraints\Choice(array('min' => 1, 'max' => 1, 'choices' => array()))),
            array(new Constraints\Choice(array('max' => 1, 'choices' => array()))),
        );
    }

    public function given_form_is_of_type($type)
    {
        $typeMock = $this->getMock('Symfony\Component\Form\ResolvedFormTypeInterface');
        $innerType = new $type();

        if (method_exists('Symfony\Component\Form\ResolvedFormTypeInterface', 'getInnerType')) {
            $typeMock->expects(self::any())->method('getInnerType')->willReturn($innerType);
        }
        if (method_exists('Symfony\Component\Form\ResolvedFormTypeInterface', 'getName')) {
            $typeMock->expects(self::any())->method('getName')->willReturn($innerType->getName());
        }

        $formConfigMock = $this->getMock('Symfony\Component\Form\FormConfigInterface');
        $formConfigMock->expects(self::any())->method('getType')->willReturn($typeMock);

        $this->form->expects(self::any())->method('getConfig')->willReturn($formConfigMock);
    }
}
