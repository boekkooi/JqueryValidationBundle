<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class DateTimeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datetime_choice', 'datetime', array(
                'label' => 'DateTime choice',
                'required' => false,
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))

            ->add('date_choice', 'date', array(
                'label' => 'Date choice',
                'required' => false,
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))
            ->add('date_text', 'date', array(
                'widget' => 'text',
                'label' => 'Date text',
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))
            ->add('date_single_text', 'date', array(
                'widget' => 'single_text',
                'label' => 'Date single text',
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))

            ->add('time_choice', 'time', array(
                'widget' => 'choice',
                'label' => 'Time choice',
                'required' => false,
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))
            ->add('time_text', 'time', array(
                'widget' => 'text',
                'label' => 'Time text',
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))
            ->add('time_single_text', 'time', array(
                'widget' => 'single_text',
                'label' => 'Time single text',
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'date_time_form';
    }
}
