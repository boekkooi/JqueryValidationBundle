<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ButtonsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'constraints' => array(
                    new Constraints\NotBlank(array('groups' => array('main', 'Default'))),
                    new Constraints\Length(array(
                        'min' => 8,
                        'max' => 200,
                        'groups' => 'main',
                    )),
                ),
            ))
            ->add('content', 'textarea', array(
                'constraints' => array(
                    new Constraints\NotBlank(),
                    new Constraints\Length(array(
                        'min' => 8,
                        'max' => 200,
                        'groups' => 'never_used',
                    )),
                ),
            ))
            ->add('defaultValidation', 'submit')
            ->add('mainValidation', 'submit', array(
                'validation_groups' => 'main',
            ))
            ->add('mainAndDefaultValidation', 'submit', array(
                'validation_groups' => array('main', 'Default'),
            ))
            ->add('noValidation', 'submit', array(
                'validation_groups' => false,
            ))
        ;
    }

    public function getName()
    {
        return 'buttons';
    }
}
