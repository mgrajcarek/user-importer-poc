<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class MandatoryMatchedFields extends Constraint
{
    public $message = 'Not all mandatory fields has been matched. Missing: [%missing%].';
    public $fields = [];

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
