<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
interface ConstraintResolverInterface
{
    public function resolve($constraints);
} 