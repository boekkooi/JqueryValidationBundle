<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

use Symfony\Component\Form\FormInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
interface ConstraintResolverInterface
{
    public function resolve($constraints, FormInterface $form);
}
