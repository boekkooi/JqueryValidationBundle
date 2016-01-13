<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\IsFalse;
use Symfony\Component\Validator\Constraints\IsTrue;

/**
 * A Rule mapper for IsTrue and IsFalse.
 *
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class IsBooleanRule implements ConstraintMapperInterface
{
    /**
     * @var bool
     */
    private $useAdditional;

    public function __construct($useAdditional)
    {
        $this->useAdditional = $useAdditional;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Constraint $constraint, FormInterface $form, RuleCollection $collection)
    {
        if (!$this->supports($constraint, $form)) {
            throw new LogicException();
        }

        $boolType = $constraint instanceof IsTrue;

        // If the isTrue is applied and it is a checkbox then just make it required
        if ($boolType && $form->getConfig()->getType()->getInnerType() instanceof CheckboxType) {
            $collection->set(
                RequiredRule::RULE_NAME,
                new ConstraintRule(
                    RequiredRule::RULE_NAME,
                    true,
                    new RuleMessage($constraint->message),
                    $constraint->groups
                )
            );

            return;
        }

        // A additional method is used to skip if not found
        if (!$this->useAdditional) {
            return;
        }

        /** @var IsTrue|IsFalse $constraint */
        $collection->set(
            'equals',
            new ConstraintRule(
                'equals',
                $boolType ? '1' : '0',
                new RuleMessage($constraint->message),
                $constraint->groups
            )
        );
    }

    public function supports(Constraint $constraint, FormInterface $form)
    {
        return in_array(
            get_class($constraint),
            array(
                IsTrue::class,
                IsFalse::class,
            ),
            true
        );
    }
}
