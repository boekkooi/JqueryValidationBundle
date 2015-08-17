<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Constraints\ValidScheduledEndDate;

class ScheduleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isScheduledEndDate', 'checkbox', array(
                'label'    => 'Is there a end date?',
                'required' => false,
            ))
            ->add('scheduledEndDate', 'datetime', array(
                'label' => 'Scheduled end date',
                'required' => false,
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
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
        return str_replace('\\', '_', __CLASS__);
    }
}
