<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Entity\Exception;

class UserException extends \Exception
{
    /**
     * @return UserException
     */
    public static function invalidData(): UserException
    {
        return new self('Invalid user data');
    }
}
