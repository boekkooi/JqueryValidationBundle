<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
interface ConstraintMapperInterface
{
    /**
     * @param RuleCollection $collection
     * @param Constraint $constraint
     */
    public function resolve(RuleCollection $collection, Constraint $constraint);

    /**
     * @param Constraint $constraint
     * @return boolean
     */
    public function supports(Constraint $constraint);
}
