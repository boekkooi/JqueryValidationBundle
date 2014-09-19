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
class EmailRule implements ConstraintMapperInterface
{
    const RULE_NAME = 'email';

    /**
     * {@inheritdoc}
     */
    public function resolve(RuleCollection $collection, Constraint $constraint, FormInterface $form)
    {
        /** @var \Symfony\Component\Validator\Constraints\Email $constraint */
        $collection->set(
            self::RULE_NAME,
            new Rule(
                self::RULE_NAME,
                true,
                new RuleMessage($constraint->message),
                $constraint->groups
            )
        );
    }

    public function supports(Constraint $constraint, FormInterface $form)
    {
        return get_class($constraint) === 'Symfony\Component\Validator\Constraints\Email';
    }
}
