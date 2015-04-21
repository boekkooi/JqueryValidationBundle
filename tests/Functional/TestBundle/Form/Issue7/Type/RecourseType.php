<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue7\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RecourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('contents', 'collection', array(
            'type' => new ContentType,
            'options' => array('label' => false),
            'cascade_validation' => true,
            'label' => false,
        ));
        $builder->add('invalidContents', 'collection', array(
            'type' => new ContentType,
            'options' => array('label' => false),
            'label' => false,
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue7\Model\Recourse'
        ));
    }

    public function getName()
    {
        return 'recourses';
    }

}
