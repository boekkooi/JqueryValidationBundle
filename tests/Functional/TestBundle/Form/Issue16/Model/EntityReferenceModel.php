<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue16\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Model\SimpleData;

class EntityReferenceModel
{
    /**
     * @var SimpleData
     *
     * @Assert\Valid()
     * @Assert\Type(type="Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Model\SimpleData")
     */
    public $entity;

    public function __construct()
    {
        $this->entity = new SimpleData();
    }
}
