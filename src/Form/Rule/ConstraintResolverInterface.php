<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
interface ConstraintResolverInterface
{
    public function resolve(ConstraintCollection $constraints, FormInterface $form);
}
