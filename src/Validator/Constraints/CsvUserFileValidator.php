<?php

namespace NetInteractive\Bundle\UserImporterBundle\Validator\Constraints;

use NetInteractive\Bundle\UserImporterBundle\Csv\Exception\FileException;
use NetInteractive\Bundle\UserImporterBundle\Csv\Reader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CsvUserFileValidator extends ConstraintValidator
{
    /**
     * @param UploadedFile $value
     * @param Constraint   $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        /* @var CsvUserFile $constraint */

        $reader = Reader::load($value);

        try {
            $headers = $reader->getHeaders();

            if (sizeof($headers) < $constraint->minFields) {
                $this->context
                    ->buildViolation($constraint->notEnoughFieldsMessage)
                    ->setParameter('%expected%', $constraint->minFields)
                    ->setParameter('%found%', sizeof($headers))
                    ->addViolation();
            }

            if (sizeof($headers) > $constraint->maxFields) {
                $this->context
                    ->buildViolation($constraint->tooManyFieldsMessage)
                    ->setParameter('%expected%', $constraint->maxFields)
                    ->setParameter('%found%', sizeof($headers))
                    ->addViolation();
            }
        } catch (FileException $e) {
            $this->context
                ->buildViolation($constraint->headersNotFoundMessage)
                ->addViolation();
        }
    }
}
