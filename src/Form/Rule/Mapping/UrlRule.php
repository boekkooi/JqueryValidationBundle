<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class UrlRule implements ConstraintMapperInterface
{
    const RULE_NAME = 'url';

    /**
     * {@inheritdoc}
     */
    public function resolve(RuleCollection $collection, Constraint $constraint)
    {
        /** @var \Symfony\Component\Validator\Constraints\Url $constraint */
        // TODO use custom pattern when protocols is not https, http, sftp, ftp
        $collection->add(
            self::RULE_NAME,
            new Rule(
                self::RULE_NAME,
                true,
                new RuleMessage($constraint->message)
            )
        );
    }

    public function supports(Constraint $constraint)
    {
        return get_class($constraint) === 'Symfony\Component\Validator\Constraints\Url';
    }
}
