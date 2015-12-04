<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue7\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RecourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('contents',
            TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\CollectionType'),
            TypeHelper::fixCollectionOptions(array(
                'entry_type' => 'Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue7\Type\ContentType',
                'entry_options' => array('label' => false),
                'constraints' => array(
                    new Valid()
                ),
                'label' => false,
            ))
        );
        $builder->add(
            'invalidContents',
            TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\CollectionType'),
            TypeHelper::fixCollectionOptions(array(
                'entry_type' => 'Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue7\Type\ContentType',
                'entry_options' => array('label' => false),
                'label' => false,
            ))
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue7\Model\Recourse'
        ));
    }

    public function getName()
    {
        return 'recourse';
    }
}
