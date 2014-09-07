<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Extension;

use Boekkooi\Bundle\JqueryValidationBundle\Form\DataConstraintFinder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormHelper;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\FormPassInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\RecursiveFormIterator;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormTypeExtension extends AbstractTypeExtension
{
    /**
     * @var FormPassInterface
     */
    private $ruleCollector;

    /**
     * @var DataConstraintFinder
     */
    private $constraintFinder;

    public function __construct(DataConstraintFinder $constraintFinder, FormPassInterface $ruleCollector, $enabled = true)
    {
        $this->ruleCollector = $ruleCollector;
        $this->constraintFinder = $constraintFinder;
        $this->defaultEnabled = $enabled;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!$this->isEnabled($view, $form, $options)) {
            return;
        }

        $validation_groups = FormHelper::getValidationGroups($form);

        // Handle the actual form root.
        if ($form->isRoot() && $view->parent === null) {
            $view->vars['jquery_validation_rules'] = new FormRuleCollection($form, $view);
            $view->vars['jquery_validation_groups'] = array();

            if ($validation_groups === null) {
                $validation_groups = array(Constraint::DEFAULT_GROUP);
            }

            $this->buildSubmitViews($view, $form);
        }

        if ($validation_groups !== null) {
            $rootView = FormHelper::getViewRoot($view);
            $rootView->vars['jquery_validation_groups'][$view->vars['full_name']] = $validation_groups;
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (!$this->isEnabled($view, $form, $options)) {
            return;
        }

        $rootCollection = $this->getRuleCollection($view);

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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('jquery_validation'));
        $resolver->setAllowedTypes(array(
            'jquery_validation' => array('bool', 'null')
        ));
    }


    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }

    protected function isEnabled(FormView $view, FormInterface $form, array $options)
    {
        $enabled = isset($options['jquery_validation']) && $options['jquery_validation'] === true || $this->defaultEnabled ? true : false;
        if ($form->isRoot() && $view->parent === null) {
            return $enabled;
        }

        $viewRoot = FormHelper::getViewRoot($view);

        // The root has no validation data so it's disabled
        if (!isset($viewRoot->vars['jquery_validation_rules'])) {
            return false;
        }
        // The jquery_validation option is not set but validation is active to this is enabled
        if (!isset($options['jquery_validation']) || $options['jquery_validation'] === null) {
            return true;
        }

        // Options was specified to return it
        return $enabled;
    }

    /**
     * @param FormView $view
     * @return FormRuleCollection
     */
    protected function getRuleCollection(FormView $view)
    {
        $viewRoot = FormHelper::getViewRoot($view);
        if (!isset($viewRoot->vars['jquery_validation_rules'])) {
            throw new \LogicException('getRuleCollection is called before it was set by buildView');
        }

        return $viewRoot->vars['jquery_validation_rules'];
    }

    /**
     * Find all constraints for the given FormInterface.
     *
     * @param FormInterface $form
     * @return array
     */
    protected function findConstraints(FormInterface $form)
    {
        $constraints = new ConstraintCollection();

        // Find constraints configured with the form
        $formConstraints = $form->getConfig()->getOption('constraints');
        if (!empty($formConstraints)) {
            if (is_array($formConstraints)) {
                $constraints->addCollection(
                    new ConstraintCollection($formConstraints)
                );
            } else {
                $constraints->add($formConstraints);
            }
        }

        // Find constraints bound by data
        if ($form->getConfig()->getMapped()) {
            $constraints->addCollection(
                $this->constraintFinder->find($form)
            );
        }

        return $constraints;
    }

    protected function buildSubmitViews(FormView $view, FormInterface $form)
    {
        // We have to walk through the entire form and detect the submit buttons
        $iterator = new RecursiveFormIterator($form);
        $iterator = new \RecursiveIteratorIterator($iterator);

        foreach ($iterator as $button) {
            /** @var FormInterface $button */
            if (!$button instanceof ClickableInterface) {
                continue;
            }

            // Create the button name.
            $name = array($button->getName());
            for ($i = $iterator->getDepth()-1; $i >= 0; $i--) {
                $name[] = $iterator->getSubIterator($i)->current()->getName();
            }
            $parentFullName = $view->vars['full_name'];
            if ($parentFullName === '') {
                $parentFullName = array_shift($name);
            }
            $name = sprintf('%s[%s]', $parentFullName, implode('][', $name));

            // Add button to the button list
            if (!isset($view->vars['jquery_validation_buttons'])) {
                $view->vars['jquery_validation_buttons'] = array();
            }
            $view->vars['jquery_validation_buttons'][] = $name;

            // Add button to the validation groups list
            if (!isset($view->vars['jquery_validation_groups'])) {
                $view->vars['jquery_validation_groups'] = array();
            }
            $view->vars['jquery_validation_groups'][$name] = FormHelper::getValidationGroups($button);
        }
    }
}
