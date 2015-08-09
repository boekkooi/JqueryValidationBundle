<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue16\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Model\RootData;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Model\SimpleData;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class EntityRefCollection
{
    /**
     * @var EntityReferenceModel[]
     *
     * @Assert\Valid()
     */
    public $entityReferences = array();

    /**
     * @Assert\Valid()
     */
    public $root = array();

    public function __construct()
    {
        $root = new RootData();
        $root->child = new SimpleData();

        $this->root[0] = $root;
    }
}
