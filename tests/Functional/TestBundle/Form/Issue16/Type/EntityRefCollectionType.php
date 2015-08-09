<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue16\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
            ->add('entityReferences', 'collection', array(
                'type' => new EmailType(),

                'allow_add' => true,
                'allow_delete' => true,

                'prototype' => true
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'issue16_collection';
    }
}
