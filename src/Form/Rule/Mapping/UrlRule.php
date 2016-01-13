<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Url;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class UrlRule implements ConstraintMapperInterface
{
    const RULE_NAME = 'url';

    /**
     * {@inheritdoc}
     */
    public function resolve(Constraint $constraint, FormInterface $form, RuleCollection $collection)
    {
        /** @var \Symfony\Component\Validator\Constraints\Url $constraint */
        if (!$this->supports($constraint, $form)) {
            throw new LogicException();
        }

        // jquery validate only validates https, http, ftp
        // So don't add the rule if there is some other protocol
        $diff = array_diff($constraint->protocols, array('http', 'https', 'ftp'));
        if (!empty($diff)) {
            return;
        }

        $collection->set(
            self::RULE_NAME,
            new ConstraintRule(
                self::RULE_NAME,
                true,
                new RuleMessage($constraint->message),
                $constraint->groups
            )
        );
    }

    public function supports(Constraint $constraint, FormInterface $form)
    {
        return get_class($constraint) === Url::class;
    }
}
