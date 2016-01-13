<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue8\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue8\Model\TestRangeEntity;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class TestRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('size', TypeHelper::type(RangeType::class), array(
            'inherit_data' => true,
            'min_name' => 'min_size',
            'max_name' => 'max_size',
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TestRangeEntity::class
        ));
    }

    public function getName()
    {
        return 'test_range';
    }
}
