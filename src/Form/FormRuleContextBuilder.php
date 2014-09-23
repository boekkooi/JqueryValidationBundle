<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;
use Symfony\Component\Form\FormView;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormRuleContextBuilder extends FormRuleContext
{
    public function __construct()
    {
        parent::__construct(array(), array(), array());
    }

    public function get($view)
    {
        $name = FormHelper::getFormName($view);

        return parent::get($name);
    }

    public function getGroup($view)
    {
        $name = FormHelper::getFormName($view);

        return parent::getGroup($name);
    }

    /**
     * @return FormRuleContext
     */
    public function getRuleContext()
    {
        return new FormRuleContext($this->rules, $this->groups, $this->buttons);
    }

    /**
     * Adds a rule collection.
     *
     * @param string|FormView $view The form full_name or view instance
     * @param RuleCollection $collection A RuleCollection instance
     */
    public function add($view, RuleCollection $collection)
    {
        $name = FormHelper::getFormName($view);
        if (isset($this->rules[$name])) {
            $this->rules[$name]->addCollection($collection);
        } else {
            $this->rules[$name] = $collection;
        }
    }

    /**
     * Removes a rule or an array of rules by name from the collection
     *
     * @param string|FormView $form The form full_name or view instance
     */
    public function remove($form)
    {
        $name = FormHelper::getFormName($form);
        unset($this->rules[$name]);
    }

    public function addButton(FormView $view, $groups = null)
    {
        $groups = $this->normalizeGroups($groups);

        $name = FormHelper::getFormName($view);
        $this->buttons[] = $name;
        $this->groups[$name] = $groups;
    }

    public function addGroup($view, $groups)
    {
        $groups = $this->normalizeGroups($groups);

        $name = FormHelper::getFormName($view);
        $this->groups[$name] = $groups;
    }

    protected function normalizeGroups($groups)
    {
        if ($groups === null) {
            return null;
        }
        if ($this->isValidGroup($groups)) {
            return array($groups);
        }

        if (!is_array($groups) && !$groups instanceof \Traversable) {
            throw new \InvalidArgumentException('A group must be a string, int, callable or FALSE.');
        }

        foreach ($groups as $group) {
            if ($this->isValidGroup($group)) {
                continue;
            }
            throw new \InvalidArgumentException('A group must be a string, int, callable or FALSE.');
        }

        return $groups;
    }
}
