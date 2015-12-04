<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ViewTransformRulesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('time_text', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\TimeType'), array(
                'widget' => 'text',
                'label' => 'Time text',
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))
            ->add('equals', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), array(
                'constraints' => array(
                    new Constraints\NotBlank(array(
                        'groups' => 'main',
                    )),
                ),
                'first_options' => array('label' => 'Text'),
                'second_options' => array('label' => 'Repeat'),
                'invalid_message' => 'Oops they don\'t match',
            ))
            ->add('defaultValidation', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\SubmitType'))
            ->add('mainValidation', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array(
                'validation_groups' => 'main',
            ))
        ;
    }

    public function getName()
    {
        return 'view_transform_rules_form';
    }
}
