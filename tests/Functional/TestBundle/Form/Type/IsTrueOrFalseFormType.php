<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

class IsTrueOrFalseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('check-true', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\CheckboxType'), array(
                'label' => 'Checkbox',
                'constraints' => array(
                    new Constraints\IsTrue()
                )
            ))
            ->add('select-bool-true', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\ChoiceType'),
                TypeHelper::fixChoices(array(
                    'label' => 'Select',
                    'choices' => array('Yes' => true, 'No' => false),
                    'constraints' => array(
                        new Constraints\IsTrue()
                    )
                ))
            )
            ->add('select-int-true', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\ChoiceType'),
                TypeHelper::fixChoices(array(
                    'label' => 'Select',
                    'choices' => array('Yes' => '1', 'No' => '0'),
                    'constraints' => array(
                        new Constraints\IsTrue()
                    )
                ))
            )
            ->add('text-true', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\TextType'), array(
                'label' => 'Text (1 === true)',
                'constraints' => array(
                    new Constraints\IsTrue()
                )
            ))

            ->add('check-false', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\CheckboxType'), array(
                'label' => 'Checkbox',
                'constraints' => array(
                    new Constraints\IsFalse()
                )
            ))
            ->add('select-false', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\ChoiceType'),
                TypeHelper::fixChoices(array(
                    'label' => 'Select',
                    'choices' => array('Yes' => '1', 'No' => '0'),
                    'constraints' => array(
                        new Constraints\IsFalse()
                    )
                ))
            )
            ->add('text-false', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\TextType'), array(
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
        return 'is_true_or_false_form';
    }
}
