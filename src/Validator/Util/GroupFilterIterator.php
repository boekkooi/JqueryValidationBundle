<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Validator\Util;

use Boekkooi\Bundle\JqueryValidationBundle\Validator\GroupCollection;
use Iterator;
use Symfony\Component\Validator\Constraint;

/**
 * A Constraint group filter iterator.
 * Used to detect if a constraint has any of the given groups.
 *
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class GroupFilterIterator extends \FilterIterator
{
    /**
     * @var GroupCollection
     */
    private $groups;

    /**
     * Constructor
     *
     * @param Iterator $iterator
     * @param GroupCollection $groupCollection
     */
    public function __construct(Iterator $iterator, GroupCollection $groupCollection)
    {
        parent::__construct($iterator);

        $this->groups = $groupCollection;
    }

    /**
     * Only accept constraint instances that have a intersecting group.
     *
     * {@inheritdoc}
     */
    public function accept()
    {
        $constraint = $this->current();
        if (!$constraint instanceof Constraint || count($constraint->groups) === 0) {
            return false;
        }

        foreach ($constraint->groups as $group) {
            if ( $this->groups->contains($group)) {
                return true;
            }
        }

        return false;
    }
}
