<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\UrlRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping\UrlRule
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class UrlRuleTest extends BaseConstraintMapperTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new UrlRule();
    }

    public function provide_supported_constraints()
    {
        return array(
            array(new Constraints\Url()),
        );
    }

    public function provide_constraint_rule_expectation()
    {
        return array(
            array(
                new Constraints\Url(array('message' => 'msg_url')),
                new ConstraintRule('url', true, new RuleMessage('msg_url'), array(Constraint::DEFAULT_GROUP)),
            ),
        );
    }
    public function provide_unsupported_constraints()
    {
        return array(
            array(new Constraints\NotBlank()),
            array(new Constraints\NotNull()),
        );
    }
}
