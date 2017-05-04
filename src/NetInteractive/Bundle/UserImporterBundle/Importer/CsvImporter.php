<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Importer;

use NetInteractive\Bundle\UserImporterBundle\Csv\ReaderInterface;
use NetInteractive\Bundle\UserImporterBundle\DTO\FieldsMatches;
use NetInteractive\Bundle\UserImporterBundle\DTO\ImportStatistics;

interface CsvImporter
{
    /**
     * @param ReaderInterface $reader
     * @param FieldsMatches   $matches
     *
     * @return ImportStatistics
     */
    public function import(ReaderInterface $reader, FieldsMatches $matches): ImportStatistics;
}
