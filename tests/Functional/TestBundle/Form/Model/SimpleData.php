<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class SimpleData
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     */
    public $name;

    /**
     * Plain password.
     *
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     */
    protected $password;
}
