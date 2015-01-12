<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\InvalidArgumentException;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormRuleContext
{
    /**
     * @var RuleCollection[] { Key: form name, Value: RuleCollection[] }
     */
    protected $rules = array();

    /**
     * @var array { Key: form name, Value: array[] }
     */
    protected $groups = array();

    /**
     * @var string[] { Value: button name }
     */
    protected $buttons = array();

    public function __construct(array $rules, array $groups, array $buttons)
    {
        foreach ($groups as $formGroups) {
            $validGroups = array_filter($formGroups, array($this, 'isValidGroup'));
            if (count($validGroups) !== count($formGroups)) {
                throw new InvalidArgumentException('Invalid groups given.');
            }
        }

        $this->rules = $rules;
        $this->groups = $groups;
        $this->buttons = $buttons;
    }

    /**
     * Gets a rule list by name.
     *
     * @param string $name The form full_name
     * @return RuleCollection|null A array of Rule instances or null when not found
     */
    public function get($name)
    {
        return isset($this->rules[$name]) ? $this->rules[$name] : null;
    }

    /**
     * Returns all rules in this collection.
     *
     * @return RuleCollection[] An array of rules
     */
    public function all()
    {
        return $this->rules;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param $name
     * @return null|array
     */
    public function getGroup($name)
    {
        return isset($this->groups[$name]) ? $this->groups[$name] : null;
    }

    public function getButtons()
    {
        return $this->buttons;
    }

    protected function isValidGroup($value)
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
