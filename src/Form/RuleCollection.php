<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\InvalidArgumentException;
use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RuleCollection extends ArrayCollection
{
    /**
     * @param int|string $key
     * @param Rule $value
     */
    public function set($key, $value)
    {
        $this->assertRuleInstance($value);

        parent::set($key, $value);
    }

    /**
     * {@inheritdoc}
     * @throw LogicException
     */
    public function add($value)
    {
        throw new LogicException('RuleCollection must be used as a dictionary');
    }

    /**
     * Adds a rule collection at the end of the current set by appending all
     * rule of the added collection.
     *
     * @param RuleCollection $collection A RuleCollection instance
     */
    public function addCollection(RuleCollection $collection)
    {
        foreach ($collection as $name => $rule) {
            $this->set($name, $rule);
        }
    }

    private function assertRuleInstance($value)
    {
        if (!$value instanceof Rule) {
            throw new InvalidArgumentException(sprintf(
                'Expected a "%s" instance',
                Rule::class
            ));
        }
    }
}
