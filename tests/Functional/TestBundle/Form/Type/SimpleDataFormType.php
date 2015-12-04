<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class SimpleDataFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\TextType'), array('label' => 'Name'))
            ->add(
                'password',
                TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\RepeatedType'),
                array('type' => TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\PasswordType'))
            )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Model\SimpleData',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'simple_data_form';
    }
}
