<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Range;

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
    public function resolve(Constraint $constraint, FormInterface $form, RuleCollection $collection)
    {
        $constraintClass = get_class($constraint);

        /** @var \Symfony\Component\Validator\Constraints\AbstractComparison $constraint */
        if ($constraintClass === GreaterThan::class) {
            $rule = new ConstraintRule(
                self::RULE_NAME,
                $constraint->value + 1, // TODO support floats
                new RuleMessage($constraint->message, array('{{ compared_value }}' => $constraint->value)),
                $constraint->groups
            );
        } elseif ($constraintClass === GreaterThanOrEqual::class) {
            $rule = new ConstraintRule(
                self::RULE_NAME,
                $constraint->value,
                new RuleMessage($constraint->message, array('{{ compared_value }}' => $constraint->value)),
                $constraint->groups
            );
        }
        /** @var Range $constraint */
        elseif ($constraintClass === Range::class && $constraint->min !== null) {
            $rule = new ConstraintRule(
                self::RULE_NAME,
                $constraint->min,
                new RuleMessage($constraint->minMessage, array('{{ limit }}' => $constraint->min)),
                $constraint->groups
            );
        } else {
            throw new LogicException();
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
                GreaterThan::class,
                GreaterThanOrEqual::class,
            ), true) ||
            $constraintClass === Range::class && $constraint->min !== null;
    }
}
