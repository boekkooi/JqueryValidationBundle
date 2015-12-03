<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class SimpleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\TextType'), array(
                'label' => 'Name',
                'constraints' => array(
                    new Constraints\NotBlank(),
                    new Constraints\Length(array('min' => 2)),
                ),
            ))
            ->add('password', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), array(
                'type' => TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                'invalid_message' => 'WRONG!',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'simple_form';
    }
}
