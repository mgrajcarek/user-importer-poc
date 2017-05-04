<?php

namespace NetInteractive\Bundle\UserImporterBundle\Csv;

interface ReaderInterface
{
    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @return array
     */
    public function getData(): array;
}
