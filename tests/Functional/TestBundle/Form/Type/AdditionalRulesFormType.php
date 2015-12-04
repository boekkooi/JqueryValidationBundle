<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;

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
                        'version' => Constraints\Ip::V4,
                    )),
                ),
            ))
            ->add('ipv6', null, array(
                'constraints' => array(
                    new Constraints\Ip(array(
                        'version' => Constraints\Ip::V6,
                    )),
                ),
            ))
            ->add('ipv4_ipv6', null, array(
                'constraints' => array(
                    new Constraints\Ip(array(
                        'version' => Constraints\Ip::ALL,
                    )),
                ),
            ))

            ->add('iban', null, array(
                'constraints' => array(
                    new Constraints\Iban(),
                ),
            ))

            ->add('luhn', null, array(
                'constraints' => array(
                    new Constraints\Luhn(),
                ),
            ))

            ->add('file', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\FileType'), array(
                'constraints' => array(
                    new Constraints\File(array(
                        'mimeTypes' => array('text/plain', 'application/pdf'),
                    )),
                ),
            ))

            ->add('pattern', TypeHelper::type('Symfony\Component\Form\Extension\Core\Type\TextType'), array(
                'constraints' => array(
                    new Constraints\Regex('/^[a-zA-Z]+$/'),
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'additional_rules_form';
    }
}
