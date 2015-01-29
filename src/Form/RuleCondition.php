<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
interface RuleCondition
{
    /**
     * Get the twig macro name to call.
     * @return string
     */
    public function macro();
}
