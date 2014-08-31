<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class CollectionPrototypePass implements FormPassInterface
{
    public function process(FormRuleCollection $collection, $constraints)
    {
        $form = $collection->getForm();
        $view = $collection->getView();

        // Check if this is a a prototype/collection type
        /** @var \Symfony\Component\Form\FormInterface|null $prototype */
        $prototype = $form->getConfig()->getAttribute('prototype');
        if (!$prototype || !isset($view->vars['prototype'])) {
            return;
        }

        /** @var \Symfony\Component\Form\FormView $prototypeView */
        $prototypeView = $view->vars['prototype'];

        // Remove the prototype rules from the default rules
        $rootCollection = $collection->isRoot() ? $collection : $collection->getRoot();

        $rules = $rootCollection->get($prototypeView);
        $rootCollection->remove($prototypeView);

        // No rules
        if (empty($rules) === null) {
            return;
        }

        $prototypeCollection = new FormRuleCollection($prototype, $prototypeView, $rootCollection);
        $prototypeCollection->add($prototypeView, $rules);
        $view->vars['jquery_validation_rules'] = $prototypeCollection;
    }
} 