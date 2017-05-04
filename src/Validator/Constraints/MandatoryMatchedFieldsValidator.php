<?php

namespace NetInteractive\Bundle\UserImporterBundle\Validator\Constraints;

use NetInteractive\Bundle\UserImporterBundle\DTO\FieldsMatches;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MandatoryMatchedFieldsValidator extends ConstraintValidator
{
    /**
     * @param FieldsMatches $value
     * @param Constraint    $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $matchings = $value->getMatchings();
        $targetFields = array_values($matchings);

        /*  @var MandatoryMatchedFields $constraint */
        $missingFields = array_diff(
            $constraint->fields,
            $targetFields
        );

        if (!empty($missingFields)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%missing%', implode(', ', $missingFields))
                ->addViolation();
        }
    }
}
