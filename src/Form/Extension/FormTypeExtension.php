<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Extension;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormDataConstraintFinder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCompilerInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormTypeExtension extends AbstractTypeExtension
{
    /**
     * @var FormDataConstraintFinder
     */
    private $constraintFinder;

    /**
     * @var boolean
     */
    private $defaultEnabled;

    /**
     * @var FormRuleCompilerInterface
     */
    private $formRuleCompiler;

    /**
     * @var FormRuleProcessorInterface
     */
    private $formRuleProcessor;

    public function __construct(FormRuleProcessorInterface $formRuleProcessor, FormRuleCompilerInterface $formRuleCompiler, FormDataConstraintFinder $constraintFinder, $enabled = true)
    {
        $this->constraintFinder = $constraintFinder;
        $this->defaultEnabled = $enabled;
        $this->formRuleCompiler = $formRuleCompiler;
        $this->formRuleProcessor = $formRuleProcessor;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // Actual form root.
        if ($form->isRoot() && $view->parent === null) {
            if (!$options['jquery_validation']) {
                return;
            }

            $validation_groups = FormHelper::getValidationGroups($form);

            $contextBuilder = new FormRuleContextBuilder();
            $view->vars['rule_builder'] = $contextBuilder;
            $view->vars['rule_builder_children'] = array();
            if ($validation_groups === null) {
                $validation_groups = array(Constraint::DEFAULT_GROUP);
            }
        } else {
            $rootView = FormHelper::getViewRoot($view);
            if (!$this->hasRuleBuilderContext($rootView)) {
                return;
            }

            $contextBuilder = $this->getRuleBuilder($rootView);
            $validation_groups = FormHelper::getValidationGroups($form);
        }

        if ($validation_groups !== null) {
            $contextBuilder->addGroup($view, $validation_groups);
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $rootView = FormHelper::getViewRoot($view);
        if (!$this->hasRuleBuilderContext($rootView)) {
            return;
        }

        $ruleBuilder = $this->getRuleBuilder($rootView);
        $this->formRuleProcessor->process(
            new FormRuleProcessorContext($view, $form, $this->findConstraints($form)),
            $ruleBuilder
        );

        if (!$this->hasRuleBuilderContext($view)) {
            return;
        }

        // Only compile to context when we are finishing the root form
        if ($rootView === $view) {
            $this->compile($view);

            // The extension requires a form to have a name
            if (!isset($view->vars['attr']['name'])) {
                $view->vars['attr']['name'] = $view->vars['full_name'];
            }

            return;
        }

        // Store child builders to be later compiled
        $rootView->vars['rule_builder_children'][] = array(
            'view' => $view,
            'builder' => $view->vars['rule_builder'],
        );
        unset($view->vars['rule_builder']);
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        if ($resolver instanceof OptionsResolver && method_exists($resolver, 'setDefault')) {
            $this->configureOptions($resolver);
            return;
        }

        // BC
        $resolver->setDefaults(array(
            'jquery_validation' => $this->defaultEnabled,
        ));
        $resolver->setAllowedTypes(array(
            'jquery_validation' => array('bool', 'null'),
        ));
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('jquery_validation', $this->defaultEnabled);
        $resolver->setAllowedTypes('jquery_validation', array('bool', 'null'));
    }

    /**
     * @inheritdoc
     */
    public function getExtendedType()
    {
        return FormHelper::isSymfony3Compatible() ? FormType::class : 'form';
    }

    /**
     * @param FormView $view
     * @return FormRuleContextBuilder
     */
    protected function getRuleBuilder(FormView $view)
    {
        $viewRoot = FormHelper::getViewRoot($view);
        if (!isset($viewRoot->vars['rule_builder'])) {
            throw new LogicException('getRuleBuilder is called before it was set by buildView');
        }

        return $viewRoot->vars['rule_builder'];
    }

    /**
     * Find all constraints for the given FormInterface.
     *
     * @param FormInterface $form
     * @return ConstraintCollection
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

    protected function hasRuleBuilderContext(FormView $view)
    {
        return isset($view->vars['rule_builder']) && $view->vars['rule_builder'] instanceof FormRuleContextBuilder;
    }

    protected function compile($view)
    {
        /** @var FormRuleContextBuilder $ruleBuilder */
        $ruleBuilder = $view->vars['rule_builder'];
        unset($view->vars['rule_builder']);

        $this->compileChildContexts($view, $ruleBuilder);
        $this->compileContext($view, $ruleBuilder);
    }

    private function compileContext(FormView $view, FormRuleContextBuilder $ruleBuilder)
    {
        $this->formRuleCompiler->compile($ruleBuilder);
        $view->vars['rule_context'] = $ruleBuilder->getRuleContext();
    }

    protected function compileChildContexts(FormView $rootView, FormRuleContextBuilder $rootRuleBuilder)
    {
        $children = $rootView->vars['rule_builder_children'];
        unset($rootView->vars['rule_builder_children']);

        foreach ($children as $child) {
            /** @var FormRuleContextBuilder $builder */
            $builder = $child['builder'];
            foreach ($rootRuleBuilder->getGroups() as $name => $groups) {
                $builder->addGroup($name, $groups);
            }

            $this->compileContext($child['view'], $builder);
        }
    }
}
