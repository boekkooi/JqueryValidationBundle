<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
interface ConstraintMapperInterface
{
    /**
     * @param Constraint $constraint
     * @param FormInterface $form
     * @param RuleCollection $collection
     * @return void
     */
    public function resolve(Constraint $constraint, FormInterface $form, RuleCollection $collection);

    /**
     * @param Constraint $constraint
     * @param FormInterface $form
     * @return boolean
     */
    public function supports(Constraint $constraint, FormInterface $form);
}
