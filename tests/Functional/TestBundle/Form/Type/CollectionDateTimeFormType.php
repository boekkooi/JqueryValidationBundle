<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class CollectionDateTimeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tags',
                TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\CollectionType'),
                TypeHelper::fixCollectionOptions(array(
                'constraints' => array(
                    new Constraints\Count(array(
                        'min' => 1,
                        'max' => 5,
                    )),
                ),

                'allow_add' => true,
                'allow_delete' => true,

                'prototype' => true,

                'entry_type' => 'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
                'entry_options' => array(
                    'widget' => 'text',
                    'constraints' => array(
                        new Constraints\NotBlank(),
                    ),
                ),
            )))
            ->add('defaultValidation', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\SubmitType'))
            ->add('mainValidation', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array(
                'validation_groups' => 'main',
            ))
        ;
    }

    public function getName()
    {
        return 'collection_date_time_form';
    }
}
