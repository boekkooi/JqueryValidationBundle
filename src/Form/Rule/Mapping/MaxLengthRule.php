<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class MaxLengthRule implements ConstraintMapperInterface
{
    const RULE_NAME = 'maxlength';

    /**
     * {@inheritdoc}
     */
    public function resolve(Constraint $constraint, FormInterface $form, RuleCollection $collection)
    {
        if (!$this->supports($constraint, $form)) {
            throw new LogicException();
        }

        /** @var \Symfony\Component\Validator\Constraints\Choice|\Symfony\Component\Validator\Constraints\Length $constraint */
        $collection->set(
            self::RULE_NAME,
            new ConstraintRule(
                self::RULE_NAME,
                $constraint->max,
                new RuleMessage($constraint->maxMessage, array('{{ limit }}' => $constraint->max), (int) $constraint->max),
                $constraint->groups
            )
        );
    }

    public function supports(Constraint $constraint, FormInterface $form)
    {
        /** @var Choice|Length $constraint */
        $constraintClass = get_class($constraint);
        if (!in_array($constraintClass, array(Choice::class, Length::class), true) ||
            $constraint->max === null ||
            $constraint->min == $constraint->max) {
            return false;
        }

        return !(
            $constraintClass === Length::class &&
            (FormHelper::isSymfony3Compatible() ?
                $this->isType($form, ChoiceType::class) :
                $this->isType($form, 'choice')
            )
        );
    }

    protected function isType(FormInterface $type, $typeName)
    {
        return FormHelper::isType($type->getConfig()->getType(), $typeName);
    }
}
