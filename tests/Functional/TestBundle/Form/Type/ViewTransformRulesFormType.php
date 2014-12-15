<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ViewTransformRulesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('time_text', 'time', array(
                'widget' => 'text',
                'label' => 'Time text',
                'constraints' => array(
                    new Constraints\NotBlank()
                ),
            ))
            ->add('equals', 'repeated', array(
                'type' => 'text',
                'constraints' => array(
                    new Constraints\NotBlank(array(
                        'groups' => 'main'
                    ))
                ),
                'first_options' => array('label' => 'Text'),
                'second_options' => array('label' => 'Repeat'),
                'invalid_message' => 'Oops they don\'t match',
            ))
            ->add('defaultValidation', 'submit')
            ->add('mainValidation', 'submit', array(
                'validation_groups' => 'main',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'view_transform_rules_form';
    }
}
