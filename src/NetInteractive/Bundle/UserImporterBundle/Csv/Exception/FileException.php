<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Csv\Exception;

use Symfony\Component\HttpFoundation\File\Exception\FileException as BaseFileException;

class FileException extends BaseFileException
{
    /**
     * @return FileException
     */
    public static function headersNotFound()
    {
        return new self('Headers not found');
    }
}
