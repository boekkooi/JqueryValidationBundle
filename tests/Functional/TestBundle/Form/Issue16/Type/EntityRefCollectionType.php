<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue16\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue16\Model\EntityRefCollection;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class EntityRefCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rooot', null, array(
                'property_path' => 'root[0].child.name',
                'label'         => 'Root[0].child.name',
            ))
            ->add('entityReferences', TypeHelper::type(CollectionType::class),
                TypeHelper::fixCollectionOptions(array(
                    'entry_type' => EmailType::class,

                    'allow_add' => true,
                    'allow_delete' => true,

                    'prototype' => true
                ))
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'entity_ref_collection';
    }
}
