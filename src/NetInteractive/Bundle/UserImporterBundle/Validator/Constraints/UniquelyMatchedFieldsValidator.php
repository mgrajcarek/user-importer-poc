<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Validator\Constraints;

use NetInteractive\Bundle\UserImporterBundle\DTO\FieldsMatches;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniquelyMatchedFieldsValidator extends ConstraintValidator
{
    /**
     * @param FieldsMatches $value
     * @param Constraint    $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $matchings = $value->getMatchings();
        $matchingsSums = array_count_values($matchings);
        $repeatedAttrs = array_filter($matchingsSums, function ($sum) {
            return $sum > 1;
        });

        if (!empty($repeatedAttrs)) {
            $repeatedAttrsNames = array_keys($repeatedAttrs);

            $this->context->buildViolation($constraint->message)
                ->setParameter('%repeated%', implode(', ', $repeatedAttrsNames))
                ->addViolation();
        }
    }
}
