<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class IsTrueOrFalseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('check-true', 'checkbox', array(
                'label' => 'Checkbox',
                'constraints' => array(
                    new Constraints\IsTrue()
                )
            ))
            ->add('select-bool-true', 'choice', array(
                'label' => 'Select',
                'choices' => array(true => 'Yes', false => 'No'),
                'constraints' => array(
                    new Constraints\IsTrue()
                )
            ))
            ->add('select-int-true', 'choice', array(
                'label' => 'Select',
                'choices' => array('1' => 'Yes', '0' => 'No'),
                'constraints' => array(
                    new Constraints\IsTrue()
                )
            ))
            ->add('text-true', 'text', array(
                'label' => 'Text (1 === true)',
                'constraints' => array(
                    new Constraints\IsTrue()
                )
            ))

            ->add('check-false', 'checkbox', array(
                'label' => 'Checkbox',
                'constraints' => array(
                    new Constraints\IsFalse()
                )
            ))
            ->add('select-false', 'choice', array(
                'label' => 'Select',
                'choices' => array('1' => 'Yes', '0' => 'No'),
                'constraints' => array(
                    new Constraints\IsFalse()
                )
            ))
            ->add('text-false', 'text', array(
                'label' => 'Text (0 === false)',
                'constraints' => array(
                    new Constraints\IsFalse()
                )
            ))
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'isTrueOrFalse';
    }
}
