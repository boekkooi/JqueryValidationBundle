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
class NumberRule implements ConstraintMapperInterface
{
    const RULE_NAME = 'number';

    /**
     * {@inheritdoc}
     */
    public function resolve(Constraint $constraint, FormInterface $form, RuleCollection $collection)
    {
        if (!$this->supports($constraint, $form)) {
            throw new \LogicException();
        }

        /** @var \Symfony\Component\Validator\Constraints\Range $constraint */
        $collection->set(
            self::RULE_NAME,
            new Rule(
                self::RULE_NAME,
                true,
                new RuleMessage($constraint->invalidMessage),
                $constraint->groups
            )
        );
    }

    public function supports(Constraint $constraint, FormInterface $form)
    {
        return get_class($constraint) === 'Symfony\Component\Validator\Constraints\Range';
    }
}
