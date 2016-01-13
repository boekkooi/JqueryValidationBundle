<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

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
            throw new LogicException();
        }

        $message = null;
        if ($constraint instanceof Range) {
            $message = new RuleMessage($constraint->invalidMessage);
        } elseif ($constraint instanceof Type) {
            $message = new RuleMessage($constraint->message, array('{{ type }}' => $constraint->type));
        }
        $collection->set(
            self::RULE_NAME,
            new ConstraintRule(
                self::RULE_NAME,
                true,
                $message,
                $constraint->groups
            )
        );
    }

    public function supports(Constraint $constraint, FormInterface $form)
    {
        $class = get_class($constraint);

        return $class === Range::class || (
            $class === Type::class &&
            in_array(strtolower($constraint->type), array('int', 'integer', 'float', 'double'), true)
        );
    }
}
