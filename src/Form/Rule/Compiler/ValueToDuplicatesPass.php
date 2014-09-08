<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Compiler;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ValueToDuplicatesPass implements FormPassInterface
{
    /**
     * @var null|\ReflectionProperty
     */
    private $keyReflCache = null;

    public function process(FormRuleCollection $collection, ConstraintCollection $constraints)
    {
        $form = $collection->getForm();
        if (!$form->getConfig()->getCompound()) {
            return;
        }

        $transformer = $this->findTransformer($form);
        if ($transformer === null) {
            return;
        }
        $keys = $this->getKeys($transformer);
        $formView = $collection->getView();

        $primary = array_shift($keys);
        $primaryView = $formView->children[$primary];

        // Copy all rules to the first child/key element
        $ruleCollection = $collection->get($formView);
        if (count($ruleCollection) > 0) {
            $collection->add(
                $primaryView,
                $ruleCollection
            );
        }
        $collection->remove($formView);

        // Get correct error message if one is set.
        $invalidMessage = null;
        if ($form->getConfig()->hasOption('invalid_message')) {
            $invalidMessage = new RuleMessage($form->getConfig()->getOption('invalid_message'));
        }

        // Create equalTo rules for all other fields
        foreach ($keys as $childName) {
            $childCollection = new RuleCollection();
            $childCollection->add(
                'equalTo',
                new Rule(
                    'equalTo',
                    $this->getFieldSelector($primaryView),
                    $invalidMessage
                )
            );

            $collection->add(
                $formView->children[$childName],
                $childCollection
            );
        }
    }

    private function findTransformer(FormInterface $form)
    {
        $transformers = $form->getConfig()->getViewTransformers();
        foreach ($transformers as $transformer) {
            if (get_class($transformer) === 'Symfony\\Component\\Form\\Extension\\Core\\DataTransformer\\ValueToDuplicatesTransformer') {
                return $transformer;
            }
        }

        return null;
    }

    private function getKeys($transformer)
    {
        if ($this->keyReflCache === null) {
            // Using reflection since we want to support more then just the repeated form type.
            $refl = new \ReflectionProperty(get_class($transformer), 'keys');
            $refl->setAccessible(true);
            $this->keyReflCache = $refl;
        }

        return $this->keyReflCache->getValue($transformer);
    }

    private function getFieldSelector(FormView $view)
    {
        // TODO move method to a selector class or something
        $vars = $view->vars;
        if (!empty($vars['attr']['id'])) {
            return trim(sprintf('#%s', $vars['attr']['id']));
        }
        if (!empty($vars['full_name'])) {
            $root = $view;
            while ($root->parent !== null) {
                $root = $root->parent;
            }

            $formSelector = '';
            if ($view === $root) {
                return sprintf('form[name="%s"]', $vars['full_name']);
            }

            $formSelector = $this->getFieldSelector($root);

            return trim(sprintf('%s *[name="%s"]', $formSelector, $vars['full_name']));
        }

        // TODO use bundle exception
        throw new \RuntimeException();
    }
}
