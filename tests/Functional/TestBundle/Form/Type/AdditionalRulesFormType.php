<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class AdditionalRulesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ipv4', null, array(
                'constraints' => array(
                    new Constraints\Ip(array(
                        'version' => Constraints\Ip::V4
                    ))
                )
            ))
            ->add('ipv6', null, array(
                'constraints' => array(
                    new Constraints\Ip(array(
                        'version' => Constraints\Ip::V6
                    ))
                )
            ))
            ->add('ipv4_ipv6', null, array(
                'constraints' => array(
                    new Constraints\Ip(array(
                        'version' => Constraints\Ip::ALL
                    ))
                )
            ))

            ->add('iban', null, array(
                'constraints' => array(
                    new Constraints\Iban()
                )
            ))

            ->add('file', 'file', array(
                'constraints' => array(
                    new Constraints\File(array(
                        'mimeTypes' => array('text/plain', 'application/pdf')
                    ))
                )
            ))
        ;
    }

    public function getName()
    {
        return 'additional_rules';
    }
}
