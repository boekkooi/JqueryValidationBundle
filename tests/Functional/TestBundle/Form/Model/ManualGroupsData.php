<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Model;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

/**
 * @Assert\GroupSequenceProvider()
 */
class ManualGroupsData implements GroupSequenceProviderInterface
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="10", groups={"lengthGroup"})
     */
    public $name;

    /**
     * @var bool
     */
    public $lengthCheck = false;

    /**
     * @inheritdoc
     */
    public function getGroupSequence()
    {
        $groups = array(Constraint::DEFAULT_GROUP);

        if ($this->lengthCheck) {
            $groups[] = 'length';
        }

        return $groups;
    }
}
