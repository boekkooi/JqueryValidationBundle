<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RootData
{
    /**
     * @var string
     *
     * @Assert\Email()
     */
    public $root;

    /**
     * @var SimpleData
     * @Assert\Valid()
     */
    public $child;

    /**
     * @var SimpleData
     */
    public $childNoValidation;
}
