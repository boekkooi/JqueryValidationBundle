<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Validator;

use Doctrine\Common\Collections\ArrayCollection;
use Traversable;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class GroupCollection extends ArrayCollection
{
    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        if (!$this->isValidGroup($value)) {
            throw new \InvalidArgumentException('A group must be a string, int, callable or FALSE.');
        }

        parent::set($key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function add($value)
    {
        if (!$this->isValidGroup($value)) {
            if (!is_array($value) && !$value instanceof \Traversable) {
                throw new \InvalidArgumentException('A group must be a string, int, callable or FALSE.');
            }

            foreach ($value as $v) {
                $this->add($v);
            }

            return true;
        }

        return parent::add($value);
    }

    private function isValidGroup($value)
    {
        return
            //  Callable
            !is_string($value) && is_callable($value) ||
            // False is allowed to deactivate validation
            is_bool($value) && $value === false ||
            // String
            is_string($value) ||
            // Int
            is_int($value);
    }
}
