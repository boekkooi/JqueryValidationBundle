<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class DateTimeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datetime_choice', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\DateTimeType'), array(
                'label' => 'DateTime choice',
                'required' => false,
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))

            ->add('date_choice', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\DateType'), array(
                'label' => 'Date choice',
                'required' => false,
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))
            ->add('date_text', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\DateType'), array(
                'widget' => 'text',
                'label' => 'Date text',
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))
            ->add('date_single_text', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\DateType'), array(
                'widget' => 'single_text',
                'label' => 'Date single text',
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))

            ->add('time_choice', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\TimeType'), array(
                'widget' => 'choice',
                'label' => 'Time choice',
                'required' => false,
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))
            ->add('time_text', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\TimeType'), array(
                'widget' => 'text',
                'label' => 'Time text',
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))
            ->add('time_single_text', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\TimeType'), array(
                'widget' => 'single_text',
                'label' => 'Time single text',
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'date_time_form';
    }
}
