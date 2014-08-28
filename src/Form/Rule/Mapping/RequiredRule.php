<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RequiredRule implements ConstraintMapperInterface
{
    const RULE_NAME = 'required';

    /**
     * {@inheritdoc}
     */
    public function resolve(RuleCollection $collection, Constraint $constraint)
    {
        /** @var \Symfony\Component\Validator\Constraints\NotBlank $constraint */
        $collection->add(
            self::RULE_NAME,
            new Rule(
                self::RULE_NAME,
                true,
                new RuleMessage($constraint->message)
            )
        );
    }

    public function supports(Constraint $constraint)
    {
        return get_class($constraint) === 'Symfony\Component\Validator\Constraints\NotBlank';
    }
}
