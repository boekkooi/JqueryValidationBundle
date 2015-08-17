<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\ValidatorBuilder;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Model\Schedule;

class ValidScheduledEndDateValidator extends ConstraintValidator
{
    /**
     * @param mixed $value The value that should be validated
     * @param ValidScheduledEndDate|Constraint $constraint The constraint for the validation
     * @throws UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$this->context instanceof ExecutionContextInterface) {
            throw new \PHPUnit_Framework_SkippedTestSuiteError('Only symfony 2.5 and higher is supported.');
        }

        if (!$constraint instanceof ValidScheduledEndDate) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ValidScheduledEndDate');
        }

        if (!$value instanceof Schedule) {
            throw new UnexpectedTypeException($value, 'Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20\Model\Schedule');
        }

        if (!$value->isScheduledEndDate) {
            return;
        }

        $validatorBuilder = new ValidatorBuilder();
        $validator = $validatorBuilder->getValidator();

        $constraintNotBlank = new NotBlank();
        $constraintNotBlank->message = $constraint->messageNotBlank;

        $validator
            ->inContext($this->context)
            ->atPath('scheduledEndDate')
            ->validate($value->scheduledEndDate, array(
                $constraintNotBlank,
            ))
        ;
    }
}
