<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue8\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add($options['min_name'], $options['type'], array_merge($options['options'], $options['min_options']));
        $builder->add($options['max_name'], $options['type'], array_merge($options['options'], $options['max_options']));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['min_name'] = $options['min_name'];
        $view->vars['max_name'] = $options['max_name'];
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'type' => 'text',
            'options' => array(),
            'min_options' => array(),
            'max_options' => array(),
            'min_name' => 'min',
            'max_name' => 'max',
            'error_bubbling' => false,
        ));

        $resolver->setAllowedTypes(array(
            'options' => 'array',
            'min_options' => 'array',
            'max_options' => 'array',
        ));
    }

    public function getName()
    {
        return 'my_range';
    }
}
