<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Constraints\ValidScheduledEndDate;

class ScheduleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isScheduledEndDate', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\CheckboxType'), array(
                'label'    => 'Is there a end date?',
                'required' => false,
            ))
            ->add('scheduledEndDate', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\DateTimeType'), array(
                'label' => 'Scheduled end date',
                'required' => false,
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Model\Schedule',
            'jquery_validation_groups' => array(
                'Default'
            ),
            'constraints' => array(
                new ValidScheduledEndDate(),
            )
        ));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'schedule_form';
    }
}
