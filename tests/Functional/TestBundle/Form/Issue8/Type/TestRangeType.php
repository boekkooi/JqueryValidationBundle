<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue8\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class TestRangeType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder->add('size', 'range', array(
//            'virtual' => true, // <- VIRTUAL OPTION
//            'min_name' => 'min_size',
//            'max_name' => 'max_size'
//        ));
        $builder->add('size', new RangeType(), array(
            'virtual' => true,
            // 'inherit_data' => true,
            'min_name' => 'min_size',
            'max_name' => 'max_size'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue8\Model\TestRangeEntity'
        ));
    }

    public function getName()
    {
        return 'test_range';
    }
}
