<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class CollectionCompoundFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'constraints' => array(
                    new Constraints\NotBlank(),
                    new Constraints\Length(array(
                        'min' => 8,
                        'max' => 200,
                    )),
                ),
            ))
            ->add('tags',
                TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\CollectionType'),
                TypeHelper::fixCollectionOptions(array(
                    'entry_type' => __NAMESPACE__ . '\SimpleFormType',
                    'allow_add' => true,
                    'allow_delete' => true,
                ))
            )
        ;
    }

    public function getName()
    {
        return 'collection_compound_form';
    }
}
