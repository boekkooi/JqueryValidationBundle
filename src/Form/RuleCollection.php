<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RuleCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Rule[]
     */
    private $rules = array();

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->rules);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->rules);
    }

    /**
     * Adds a rule.
     *
     * @param string $name  The rule name
     * @param Rule  $rule A Rule instance
     */
    public function add($name, Rule $rule)
    {
        unset($this->rules[$name]);

        $this->rules[$name] = $rule;
    }

    /**
     * Returns all rules in this collection.
     *
     * @return Rule[] An array of rules
     */
    public function all()
    {
        return $this->rules;
    }

    /**
     * Gets a rule list by name.
     *
     * @param string $name  The rule name
     * @return Rule|null A array of Rule instances or null when not found
     */
    public function get($name)
    {
        return isset($this->rules[$name]) ? $this->rules[$name] : null;
    }

    /**
     * Removes a rule or an array of rules by name from the collection
     *
     * @param string|array $name The rule name or an array of rule names
     */
    public function remove($name)
    {
        foreach ((array) $name as $n) {
            unset($this->rules[$n]);
        }
    }

    /**
     * Adds a rule collection at the end of the current set by appending all
     * rule of the added collection.
     *
     * @param RuleCollection $collection A RuleCollection instance
     */
    public function addCollection(RuleCollection $collection)
    {
        foreach ($collection->all() as $name => $rule) {
            unset($this->rules[$name]);
            $this->rules[$name] = $rule;
        }
    }
}