<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ConstraintResolver implements ConstraintResolverInterface
{
    /**
     * @var ConstraintMapperInterface[]
     */
    protected $mappers = array();

    public function resolve($constraints, FormInterface $form)
    {
        $collection = new RuleCollection();
        foreach ($this->mappers as $mapper) {
            foreach ($constraints as $constraint) {
                if (!$mapper->supports($constraint, $form)) {
                    continue;
                }

                $mapper->resolve($collection, $constraint, $form);
            }
        }

        return $collection;
    }

    public function addDefaultMappers()
    {
        $this->mappers = array_merge(
            $this->mappers,
            array(
                new Mapping\RequiredRule(),

                new Mapping\NumberRule(),
                new Mapping\MinRule(),
                new Mapping\MaxRule(),

                new Mapping\MinLengthRule(),
                new Mapping\MaxLengthRule(),

                new Mapping\EmailRule(),
                new Mapping\UrlRule(),
                new Mapping\CreditcardRule()
            )
        );
    }

    public function addMapper(ConstraintMapperInterface $mapper)
    {
        $this->mappers[] = $mapper;
    }
}


