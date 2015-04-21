<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue7\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class Recourse
{
    /**
     * @var Content[]
     *
     * @Assert\Valid()
     */
    private $contents;

    /**
     * @var Content[]
     */
    private $invalidContents;

    /**
     * @return Content[]
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param Content[] $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    /**
     * @return Content[]
     */
    public function getInvalidContents()
    {
        return $this->invalidContents;
    }

    /**
     * @param Content[] $invalidContents
     */
    public function setInvalidContents($invalidContents)
    {
        $this->invalidContents = $invalidContents;
    }
}
