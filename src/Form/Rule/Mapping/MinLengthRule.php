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
class MinLengthRule implements ConstraintMapperInterface
{
    const RULE_NAME = 'minlength';

    /**
     * {@inheritdoc}
     */
    public function resolve(RuleCollection $collection, Constraint $constraint)
    {
        /** @var \Symfony\Component\Validator\Constraints\Choice|\Symfony\Component\Validator\Constraints\Length $constraint */
        $collection->add(
            self::RULE_NAME,
            new Rule(
                self::RULE_NAME,
                $constraint->min,
                new RuleMessage($constraint->minMessage, array('{{ limit }}' => $constraint->min), (int)$constraint->min)
            )
        );
    }

    public function supports(Constraint $constraint)
    {
        return
            in_array(get_class($constraint), [
                'Symfony\Component\Validator\Constraints\Choice',
                'Symfony\Component\Validator\Constraints\Length'
            ], true) &&
            $constraint->min !== null &&
            $constraint->min != $constraint->max;
    }
}
