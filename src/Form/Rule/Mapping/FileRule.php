<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Form\FormInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FileRule implements ConstraintMapperInterface
{
    const RULE_NAME = 'accept';

    /**
     * @var bool
     */
    private $active;

    public function __construct($active)
    {
        $this->active = $active;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Constraint $constraint, FormInterface $form, RuleCollection $collection)
    {
        if (!$this->supports($constraint, $form)) {
            throw new LogicException();
        }

        /** @var \Symfony\Component\Validator\Constraints\File $constraint */
        $collection->set(
            self::RULE_NAME,
            new ConstraintRule(
                self::RULE_NAME,
                implode(',', $constraint->mimeTypes),
                new RuleMessage($constraint->mimeTypesMessage, array(
                    '{{ types }}' => implode(', ', $constraint->mimeTypes),
                )),
                $constraint->groups
            )
        );
    }

    public function supports(Constraint $constraint, FormInterface $form)
    {
        /** @var \Symfony\Component\Validator\Constraints\File $constraint */
        return $this->active &&
            get_class($constraint) === 'Symfony\Component\Validator\Constraints\File' &&
            !empty($constraint->mimeTypes);
    }
}
