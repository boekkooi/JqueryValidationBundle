<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Validator;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\Util\GroupFilterIterator;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormContext
{
    /**
     * @var ConstraintCollection
     */
    private $constraints;

    /**
     * @var GroupCollection
     */
    private $groups;

    public function __construct(ConstraintCollection $constraints, GroupCollection $groups)
    {
        $this->constraints = $constraints;
        $this->groups = $groups;
    }

    /**
     *
     * @return bool
     */
    public function isDisabled()
    {
        return $this->groups->contains(false);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if ($this->isDisabled() || $this->groups->count() === 0) {
            return new \EmptyIterator();
        }

        return new GroupFilterIterator(
            $this->constraints->getIterator(),
            $this->groups
        );
    }

    /**
     * @return ConstraintCollection
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * @return GroupCollection|null
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
