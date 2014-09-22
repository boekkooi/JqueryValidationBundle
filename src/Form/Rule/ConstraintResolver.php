<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;

use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ConstraintResolver implements ConstraintResolverInterface
{
    /**
     * @var ConstraintMapperInterface[]
     */
    protected $mappers = array();

    public function resolve(ConstraintCollection $constraints, FormInterface $form)
    {
        $collection = new RuleCollection();
        foreach ($this->mappers as $mapper) {
            foreach ($constraints as $constraint) {
                if (!$mapper->supports($constraint, $form)) {
                    continue;
                }

                $mapper->resolve($constraint, $form, $collection);
            }
        }

        return $collection;
    }

    public function addMapper(ConstraintMapperInterface $mapper)
    {
        $this->mappers[] = $mapper;
    }
}
