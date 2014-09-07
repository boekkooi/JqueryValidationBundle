<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ConstraintCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Constraint[]
     */
    private $constraints = array();

    public function __construct(array $constraints = array())
    {
        foreach ($constraints as $constraint) {
            $this->add($constraint);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->constraints);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->constraints);
    }

    /**
     * Adds a constraint.
     *
     * @param Constraint  $constraint A Constraint instance
     */
    public function add(Constraint $constraint)
    {
        $this->constraints[] = $constraint;
    }

    /**
     * Returns all constraints in this collection.
     *
     * @return Constraint[] An array of constraints
     */
    public function all()
    {
        return $this->constraints;
    }

    /**
     * Gets a constraint list by it's index.
     *
     * @param int $index The constraint index
     * @return Constraint|null A Constraint instance or null when not found
     */
    public function get($index)
    {
        return isset($this->constraints[$index]) ? $this->constraints[$index] : null;
    }

    /**
     * Removes a constraint or an array of constraints by name from the collection
     *
     * @param int|array $indexes The constraint index or an array of constraint indexes
     */
    public function remove($indexes)
    {
        foreach ((array) $indexes as $i) {
            unset($this->constraints[$i]);
        }
    }

    public function clear()
    {
        $this->constraints = array();
    }

    /**
     * Adds a constraint collection at the end of the current set by appending all
     * constraint of the added collection.
     *
     * @param ConstraintCollection $collection A ConstraintCollection instance
     */
    public function addCollection(ConstraintCollection $collection)
    {
        foreach ($collection->all() as $constraint) {
            $this->constraints[] = $constraint;
        }
    }
}
