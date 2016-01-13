<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

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
            ->add('content', TypeHelper::type(TextareaType::class), array(
                'constraints' => array(
                    new Constraints\NotBlank(),
                    new Constraints\Length(array(
                        'min' => 8,
                        'max' => 200,
                        'groups' => 'never_used',
                    )),
                ),
            ))
            ->add('defaultValidation', TypeHelper::type(SubmitType::class))
            ->add('mainValidation', TypeHelper::type(SubmitType::class), array(
                'validation_groups' => 'main',
            ))
            ->add('mainAndDefaultValidation', TypeHelper::type(SubmitType::class), array(
                'validation_groups' => array('main', 'Default'),
            ))
            ->add('noValidation', TypeHelper::type(SubmitType::class), array(
                'validation_groups' => false,
            ))
        ;
    }

    public function getName()
    {
        return 'buttons_form';
    }
}
