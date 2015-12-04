<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

class ManualGroupsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\TextType'), array(
                'label' => 'Name',
                'constraints' => array(
                    new Constraints\NotBlank(),
                    new Constraints\Length(array(
                        'min' => 2,
                        'max' => 3,
                        'groups' => 'lengthGroup'
                    )),
                )
            ))
            ->add('lengthCheck', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\CheckboxType'), array(
                'label' => 'Enable length validation group',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Model\ManualGroupsData',
            'jquery_validation_groups' => array(
                Constraint::DEFAULT_GROUP,
                'lengthGroup'
            )
        ));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'manual_groups_form';
    }
}
