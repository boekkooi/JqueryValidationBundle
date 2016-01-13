<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Ip;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class IpRule implements ConstraintMapperInterface
{
    const RULE_NAME_V4 = 'ipv4';
    const RULE_NAME_V6 = 'ipv6';
    const RULE_NAME_OR = 'one_or_other';

    /**
     * @var bool
     */
    private $useIpv4;

    /**
     * @var bool
     */
    private $useIpv6;

    /**
     * @var bool
     */
    private $useOrRule;

    public function __construct($useIpv4, $useIpv6, $useOrRule)
    {
        $this->useIpv4 = $useIpv4;
        $this->useIpv6 = $useIpv6;
        $this->useOrRule = $useOrRule;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Constraint $constraint, FormInterface $form, RuleCollection $collection)
    {
        if (!$this->supports($constraint, $form)) {
            throw new LogicException();
        }

        /** @var Ip $constraint */
        $ruleOptions = true;
        switch ($constraint->version) {
            case Ip::V4:
            case Ip::V4_NO_PRIV:
            case Ip::V4_NO_RES:
            case Ip::V4_ONLY_PUBLIC:
                if (!$this->useIpv4) {
                    return;
                }
                $ruleName = self::RULE_NAME_V4;
                break;

            case Ip::V6:
            case Ip::V6_NO_PRIV:
            case Ip::V6_NO_RES:
            case Ip::V6_ONLY_PUBLIC:
                if (!$this->useIpv6) {
                    return;
                }
                $ruleName = self::RULE_NAME_V6;
                break;

            case Ip::ALL:
            case Ip::ALL_NO_PRIV:
            case Ip::ALL_NO_RES:
            case Ip::ALL_ONLY_PUBLIC:
                if (!$this->useOrRule || !$this->useIpv6 || !$this->useIpv4) {
                    return;
                }
                $ruleName = self::RULE_NAME_OR;
                $ruleOptions = array(self::RULE_NAME_V4 => true, self::RULE_NAME_V6 => true);
                break;
            default:
                return;
        }

        $collection->set(
            'ip',
            new ConstraintRule(
                $ruleName,
                $ruleOptions,
                new RuleMessage($constraint->message),
                $constraint->groups
            )
        );
    }

    public function supports(Constraint $constraint, FormInterface $form)
    {
        return get_class($constraint) === Ip::class &&
            ($this->useIpv6 || $this->useIpv4);
    }
}
