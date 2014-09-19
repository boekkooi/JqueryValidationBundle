<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class MinRule implements ConstraintMapperInterface
{
    const RULE_NAME = 'min';

    /**
     * {@inheritdoc}
     * @param
     */
    public function resolve(RuleCollection $collection, Constraint $constraint, FormInterface $form)
    {
        $constraintClass = get_class($constraint);

        /** @var \Symfony\Component\Validator\Constraints\AbstractComparison $constraint */
        if ($constraintClass === 'Symfony\Component\Validator\Constraints\GreaterThan') {
            $rule = new Rule(
                self::RULE_NAME,
                $constraint->value + 1, // TODO support floats
                new RuleMessage($constraint->message, array('{{ compared_value }}', $constraint->value)),
                $constraint->groups
            );
        } elseif ($constraintClass === 'Symfony\Component\Validator\Constraints\GreaterThanOrEqual') {
            $rule = new Rule(
                self::RULE_NAME,
                $constraint->value,
                new RuleMessage($constraint->message, array('{{ compared_value }}', $constraint->value)),
                $constraint->groups
            );
        }
        /** @var \Symfony\Component\Validator\Constraints\Range $constraint */
        elseif ($constraintClass === 'Symfony\Component\Validator\Constraints\Range' && $constraint->min !== null) {
            $rule = new Rule(
                self::RULE_NAME,
                $constraint->min,
                new RuleMessage($constraint->minMessage, array('{{ limit }}', $constraint->min)),
                $constraint->groups
            );
        } else {
            // TODO use bundle exception
            throw new \LogicException();
        }

        $collection->set(
            self::RULE_NAME,
            $rule
        );
    }

    public function supports(Constraint $constraint, FormInterface $form)
    {
        $constraintClass = get_class($constraint);

        return
            in_array($constraintClass, array(
                'Symfony\Component\Validator\Constraints\GreaterThan',
                'Symfony\Component\Validator\Constraints\GreaterThanOrEqual'
            ), true) ||
            $constraintClass === 'Symfony\Component\Validator\Constraints\Range' && $constraint->min !== null;
    }
}
