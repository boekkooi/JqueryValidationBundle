<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue8\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class TestRangeEntity
{
    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(value=400)
     */
    private $minSize = 400;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\LessThanOrEqual(value=2000)
     */
    private $maxSize = 700;

    /**
     * @return int
     */
    public function getMinSize()
    {
        return $this->minSize;
    }

    /**
     * @param int $minSize
     */
    public function setMinSize($minSize)
    {
        $this->minSize = $minSize;
    }

    /**
     * @return int
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }

    /**
     * @param int $maxSize
     */
    public function setMaxSize($maxSize)
    {
        $this->maxSize = $maxSize;
    }
}
