<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UniquelyMatchedFields extends Constraint
{
    public $message = 'Multiple use of the same target attribute detected. Check usage of table attributes: [%repeated%].';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
