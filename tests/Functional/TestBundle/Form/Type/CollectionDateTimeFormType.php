<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class CollectionDateTimeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tags', 'collection', array(
                'type' => 'datetime',
                'constraints' => array(
                    new Constraints\Count(array(
                        'min' => 1,
                        'max' => 5
                    ))
                ),

                'allow_add' => true,
                'allow_delete' => true,

                'prototype' => true,
                'options' => array(
                    'widget' => 'text',
                    'constraints' => array(
                        new Constraints\NotBlank()
                    )
                )
            ))
            ->add('defaultValidation', 'submit')
            ->add('mainValidation', 'submit', array(
                'validation_groups' => 'main',
            ))
        ;
    }

    public function getName()
    {
        return 'collection_date_time';
    }
}
