<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Schedule
{
    /**
     * @var bool
     */
    public $isScheduledEndDate = false;

    /**
     * @var null|\DateTime
     */
    public $scheduledEndDate;
}
