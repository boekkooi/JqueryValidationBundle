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
class RootDataFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('root', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\TextType'), array('label' => 'Root Name'))
            ->add('child', TypeHelper::type(__NAMESPACE__ . '\SimpleDataFormType'))
            ->add('childNoValidation', TypeHelper::type(__NAMESPACE__ . '\SimpleDataFormType'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Model\RootData',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'root_data_form';
    }
}
