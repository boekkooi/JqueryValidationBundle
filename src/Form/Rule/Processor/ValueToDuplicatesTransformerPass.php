<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\TransformerRule;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ValueToDuplicatesTransformerPass implements FormRuleProcessorInterface
{
    /**
     * @var null|\ReflectionProperty
     */
    private $keyReflectionCache = null;

    public function process(FormRuleProcessorContext $context, FormRuleContextBuilder $collection)
    {
        $form = $context->getForm();
        if (!$form->getConfig()->getCompound()) {
            return;
        }

        $transformer = $this->findTransformer($form);
        if ($transformer === null) {
            return;
        }
        $keys = $this->getKeys($transformer);
        $formView = $context->getView();

        $primary = array_shift($keys);
        $primaryView = $formView->children[$primary];

        // Copy all rules to the first child/key element
        $ruleCollection = $collection->get($formView);
        if (!empty($ruleCollection)) {
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
            $childCollection->set(
                'equalTo',
                new TransformerRule(
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
        if ($this->keyReflectionCache === null) {
            // Using reflection since we want to support more then just the repeated form type.
            $reflection = new \ReflectionProperty(get_class($transformer), 'keys');
            $reflection->setAccessible(true);
            $this->keyReflectionCache = $reflection;
        }

        return $this->keyReflectionCache->getValue($transformer);
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

            if ($view === $root) {
                return sprintf('form[name="%s"]', $vars['full_name']);
            }

            $formSelector = $this->getFieldSelector($root);

            return trim(sprintf('%s *[name="%s"]', $formSelector, $vars['full_name']));
        }

        throw new LogicException();
    }
}
