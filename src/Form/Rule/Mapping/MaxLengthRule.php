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
class MaxLengthRule implements ConstraintMapperInterface
{
    const RULE_NAME = 'maxlength';

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
                $constraint->max,
                new RuleMessage($constraint->maxMessage, array('{{ limit }}' => $constraint->max), (int)$constraint->max)
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
            $constraint->max !== null &&
            $constraint->min != $constraint->max;
    }
}
