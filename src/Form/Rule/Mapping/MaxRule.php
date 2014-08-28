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
class MaxRule implements ConstraintMapperInterface
{
    const RULE_NAME = 'max';

    /**
     * {@inheritdoc}
     * @param
     */
    public function resolve(RuleCollection $collection, Constraint $constraint)
    {
        /** @var \Symfony\Component\Validator\Constraints\AbstractComparison $constraint */
        $constraintClass = get_class($constraint);
        if ($constraintClass === 'Symfony\Component\Validator\Constraints\LessThan') {
            $rule = new Rule(
                self::RULE_NAME,
                $constraint->value - 1, // TODO support floats
                new RuleMessage($constraint->message, array('{{ compared_value }}', $constraint->value))
            );
        }
        elseif ($constraintClass === 'Symfony\Component\Validator\Constraints\LessThanOrEqual') {
            $rule = new Rule(
                self::RULE_NAME,
                $constraint->value,
                new RuleMessage($constraint->message, array('{{ compared_value }}', $constraint->value))
            );
        }
        /** @var \Symfony\Component\Validator\Constraints\Range $constraint */
        elseif ($constraintClass === 'Symfony\Component\Validator\Constraints\Range' && $constraint->max !== null) {
            $rule = new Rule(
                self::RULE_NAME,
                $constraint->max,
                new RuleMessage($constraint->maxMessage, array('{{ limit }}', $constraint->max))
            );
        } else {
            // TODO use bundle exception
            throw new \LogicException();
        }

        $collection->add(
            self::RULE_NAME,
            $rule
        );
    }

    public function supports(Constraint $constraint)
    {
        $constraintClass = get_class($constraint);

        return
            in_array($constraintClass, [
                'Symfony\Component\Validator\Constraints\LessThan',
                'Symfony\Component\Validator\Constraints\LessThanOrEqual'
            ], true) ||
            $constraintClass === 'Symfony\Component\Validator\Constraints\Range' && $constraint->max !== null
        ;
    }
}

