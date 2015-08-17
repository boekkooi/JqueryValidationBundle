<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Constraints;

use Symfony\Component\Validator\Constraint;

class ValidScheduledEndDate extends Constraint
{
    public $messageNotBlank = 'The scheduled end date may not be empty.';

    public $messageDate = 'The scheduled end date is not a valid date.';
}
