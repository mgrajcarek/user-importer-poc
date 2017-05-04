<?php

namespace spec\NetInteractive\Bundle\UserImporterBundle\Entity\Factory;

use NetInteractive\Bundle\UserImporterBundle\DTO\FieldsMatches;
use NetInteractive\Bundle\UserImporterBundle\Entity\Exception\UserException;
use NetInteractive\Bundle\UserImporterBundle\Entity\Factory\UserFactory;
use NetInteractive\Bundle\UserImporterBundle\Entity\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UserFactory::class);
    }

    public function let(ValidatorInterface $validator)
    {
        $this->beConstructedWith($validator);
    }

    public function it_should_set_User_attributes_according_to_fields_matches_specification(
        ValidatorInterface $validator,
        ConstraintViolationListInterface $violationList
    ) {
        // Given
        $matches = new FieldsMatches();
        $matches->Username = 'username';
        $matches->Surname = 'surname';
        $matches->GivenName = 'givenName';

        $data = [
            'Username' => 'jrambo',
            'Surname' => 'Rambo',
            'Password' => 'ChuckNorrisSucks',
            'GivenName' => 'Johny',
        ];

        $violationList->count()
            ->willReturn(0);

        $validator->validate(Argument::type(User::class))
            ->willReturn($violationList);

        // When
        $user = $this->create($data, $matches);

        // Then
        $user->getUsername()->shouldReturn('jrambo');
        $user->getSurname()->shouldReturn('Rambo');
        $user->getGivenName()->shouldReturn('Johny');
        $user->getPassword()->shouldBeNull();
    }

    public function it_should_throw_UserException_if_User_entity_is_invalid(
        ValidatorInterface $validator,
        ConstraintViolationListInterface $violationList
    ) {
        // Given
        $matches = new FieldsMatches();
        $matches->Username = 'username';

        $data = [
            'Username' => 'jrambo',
            'Surname' => 'Rambo',
            'Password' => 'ChuckNorrisSucks',
            'GivenName' => 'Johny',
        ];

        $violationList->count()
            ->willReturn(1);

        $validator->validate(Argument::type(User::class))
            ->willReturn($violationList);

        // Then
        $this->shouldThrow(UserException::invalidData())

        // When
            ->duringCreate($data, $matches);
    }
}
