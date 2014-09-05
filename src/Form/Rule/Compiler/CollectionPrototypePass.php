<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class CollectionPrototypePass implements FormPassInterface
{
    public function process(FormRuleCollection $collection, ConstraintCollection $constraints)
    {
        $form = $collection->getForm();
        $view = $collection->getView();

        // Check if this is a a prototype/collection type
        /** @var FormInterface|null $prototype */
        $prototype = $form->getConfig()->getAttribute('prototype');
        if (!$prototype || !isset($view->vars['prototype'])) {
            return;
        }

        /** @var FormView $prototypeView */
        $prototypeView = $view->vars['prototype'];

        // Extract the prototype rules from the default rules
        $rootCollection = $collection->isRoot() ? $collection : $collection->getRoot();
        $prototypeCollection = $this->extractRules($rootCollection, $prototype, $prototypeView);
        if ($prototypeCollection->count() > 0) {
            $view->vars['jquery_validation_rules'] = $prototypeCollection;
        }
    }

    /**
     * @param FormRuleCollection $collection
     * @param FormInterface $form
     * @param FormView $view
     * @return \Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection
     */
    protected function extractRules(FormRuleCollection $collection, FormInterface $form, FormView $view)
    {
        $extracted = new FormRuleCollection($form, $view, $collection);
        if ($form->getConfig()->getCompound()) {
            $prefix = $view->vars['full_name'];

            $found = [];
            foreach ($collection as $name => $rules) {
                if (strpos($name, $prefix) === 0) {
                    $found[] = $name;
                }
            }
        } else {
            $found = [$view];
        }

        foreach ($found as $foundView) {
            $rules = $collection->get($foundView);
            if ($rules === null) {
                continue;
            }

            $extracted->add($foundView, $rules);
            $collection->remove($foundView);
        }

        return $extracted;
    }
}
