<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Extension;

use Boekkooi\Bundle\JqueryValidationBundle\Form\DataConstraintFinder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ValidationTypeExtension extends AbstractTypeExtension
{
    /**
     * @var FormPassInterface
     */
    private $ruleCollector;

    /**
     * @var DataConstraintFinder
     */
    private $constraintFinder;

    public function __construct(DataConstraintFinder $constraintFinder, FormPassInterface $ruleCollector)
    {
        $this->ruleCollector = $ruleCollector;
        $this->constraintFinder = $constraintFinder;
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $rootCollection = $this->getRuleCollection($view, $form);

        // Detect the root form. This is not the same FormInterface (see the collection implementation)
        if ($form->isRoot() && $view->parent === null) {
            $collection = $rootCollection;

            $this->ruleCollector->process(
                $collection,
                $this->findConstraints($form)
            );
        } else {
            $collection = new FormRuleCollection($form, $view, $rootCollection);

            $this->ruleCollector->process(
                $collection,
                $this->findConstraints($form)
            );

            $rootCollection->addCollection($collection);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }

    /**
     * @param FormView $view
     * @return FormRuleCollection
     */
    private function getRuleCollection(FormView $view, FormInterface $form)
    {
        $viewRoot = $this->getViewRoot($view);
        if (!isset($viewRoot->vars['jquery_validation_rules'])) {
            $viewRoot->vars['jquery_validation_rules'] = new FormRuleCollection($form->getRoot(), $viewRoot);
        }

        return $viewRoot->vars['jquery_validation_rules'];
    }

    /**
     * @param FormView $view
     * @return FormView
     */
    private function getViewRoot(FormView $view)
    {
        $root = $view;
        while ($root->parent !== null) {
            $root = $root->parent;
        }
        return $root;
    }

    /**
     * Find all constraints for the given FormInterface.
     *
     * @param FormInterface $form
     * @return array
     */
    protected function findConstraints(FormInterface $form)
    {
        $constraints = [];

        // Find constraints configured with the form
        $formConstraints = $form->getConfig()->getOption('constraints');
        if (!empty($formConstraints)) {
            if (!is_array($formConstraints)) {
                $formConstraints = [$formConstraints];
            }
            $constraints = array_merge(
                $constraints,
                $formConstraints
            );
        }

        // Find constraints bound by data
        if ($form->getConfig()->getMapped()) {
            $constraints = array_merge(
                $constraints,
                $this->constraintFinder->find($form)
            );
        }

        return $constraints;
    }
}
