<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Validator;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\InvalidArgumentException;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ConstraintCollection extends ArrayCollection
{
    /**
     * {@inheritDoc}
     *
     * @param Constraint $value A Constraint instance
     */
    public function set($key, $value)
    {
        $this->assertConstraintInstance($value);

        parent::set($key, $value);
    }

    /**
     * {@inheritDoc}
     *
     * @param Constraint $value A Constraint instance
     */
    public function add($value)
    {
        $this->assertConstraintInstance($value);

        return parent::add($value);
    }

    /**
     * Adds a constraint collection at the end of the current set by appending all
     * constraint of the added collection.
     *
     * @param ConstraintCollection $collection A ConstraintCollection instance
     */
    public function addCollection(ConstraintCollection $collection)
    {
        foreach ($collection as $constraint) {
            $this->add($constraint);
        }
    }

    /**
     * @param $value
     */
    private function assertConstraintInstance($value)
    {
        if (!$value instanceof Constraint) {
            throw new InvalidArgumentException(sprintf(
                'Expected a "%s" instance',
                Constraint::class
            ));
        }
    }
}
