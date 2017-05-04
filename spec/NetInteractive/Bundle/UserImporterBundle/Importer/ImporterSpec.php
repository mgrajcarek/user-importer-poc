<?php

namespace spec\NetInteractive\Bundle\UserImporterBundle\Importer;

use Doctrine\ORM\EntityManagerInterface;
use NetInteractive\Bundle\UserImporterBundle\Csv\ReaderInterface;
use NetInteractive\Bundle\UserImporterBundle\DTO\FieldsMatches;
use NetInteractive\Bundle\UserImporterBundle\Entity\Exception\UserException;
use NetInteractive\Bundle\UserImporterBundle\Entity\Factory\UserFactory;
use NetInteractive\Bundle\UserImporterBundle\Entity\User;
use NetInteractive\Bundle\UserImporterBundle\Importer\Importer;
use PhpSpec\ObjectBehavior;

class ImporterSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Importer::class);
    }

    public function let(UserFactory $userFactory, EntityManagerInterface $em)
    {
        $this->beConstructedWith($userFactory, $em);
    }

    public function it_should_import_Users_and_return_statistics(
        UserFactory $userFactory,
        EntityManagerInterface $em,
        ReaderInterface $reader
    ) {
        // Given
        $matches = new FieldsMatches();

        $reader->getData()
            ->willReturn([
                $ud1 = ['Username' => 'jrambo', 'GivenName' => 'Johny', 'Surname' => 'Rambo'],
                $ud2 = ['Username' => 'cnorris', 'GivenName' => 'Chuck', 'Surname' => 'Norris'],
                $ud3 = ['GivenName' => 'Unknown', 'Surname' => 'Hero'],
            ]);

        $userFactory->create($ud1, $matches)
            ->willReturn($user1 = new User());

        $userFactory->create($ud2, $matches)
            ->willReturn($user2 = new User());

        $userFactory->create($ud3, $matches)
            ->willThrow(UserException::invalidData());

        // When
        $statistics = $this->import($reader, $matches);

        // Then
        $statistics->duration->shouldNotBeNull();
        $statistics->successful->shouldBe(2);
        $statistics->failed->shouldBe(1);

        $em->beginTransaction()->shouldHaveBeenCalled();
        $em->persist($user1)->shouldHaveBeenCalled();
        $em->persist($user2)->shouldHaveBeenCalled();
        $em->flush()->shouldHaveBeenCalled();
        $em->commit()->shouldHaveBeenCalled();
    }
}
