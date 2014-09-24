<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RootDataFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('root', 'text', array('label' => 'Root Name'))
            ->add('child', new SimpleDataFormType())
            ->add('childNoValidation', new SimpleDataFormType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Model\RootData'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'root_form';
    }
}
