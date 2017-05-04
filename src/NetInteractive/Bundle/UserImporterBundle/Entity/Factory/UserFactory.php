<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Entity\Factory;

use NetInteractive\Bundle\UserImporterBundle\DTO\FieldsMatches;
use NetInteractive\Bundle\UserImporterBundle\Entity\Exception\UserException;
use NetInteractive\Bundle\UserImporterBundle\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserFactory
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param array         $data
     * @param FieldsMatches $matches
     *
     * @throws UserException
     *
     * @return User
     */
    public function create($data, FieldsMatches $matches): User
    {
        $user = new User();

        foreach ($matches->getMatchings() as $field => $attribute) {
            $user->{'set'.ucfirst($attribute)}(
                $data[$field]
            );
        }

        $violations = $this->validator->validate($user);
        if (count($violations) > 0) {
            throw UserException::invalidData();
        }

        return $user;
    }
}
