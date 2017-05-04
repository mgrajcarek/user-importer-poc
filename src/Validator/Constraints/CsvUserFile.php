<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class CsvUserFile extends Constraint
{
    public $headersNotFoundMessage = 'Headers not found in a file';
    public $notEnoughFieldsMessage = 'Not enough fields found. Expected minimum %expected%, got %found%';
    public $tooManyFieldsMessage = 'Too many fields found. Expected maximum %expected%, got %found%';

    public $minFields = 2;
    public $maxFields = 50;
}
