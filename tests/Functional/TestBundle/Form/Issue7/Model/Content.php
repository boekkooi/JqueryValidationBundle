<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue7\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class Content
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=8196)
     */
    private $message;

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
