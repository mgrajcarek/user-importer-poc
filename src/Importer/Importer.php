<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Importer;

use Doctrine\ORM\EntityManagerInterface;
use NetInteractive\Bundle\UserImporterBundle\Csv\ReaderInterface;
use NetInteractive\Bundle\UserImporterBundle\DTO\FieldsMatches;
use NetInteractive\Bundle\UserImporterBundle\DTO\ImportStatistics;
use NetInteractive\Bundle\UserImporterBundle\Entity\Exception\UserException;
use NetInteractive\Bundle\UserImporterBundle\Entity\Factory\UserFactory;
use Symfony\Component\Stopwatch\Stopwatch;

class Importer implements CsvImporter
{
    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Stopwatch
     */
    private $timer;

    /**
     * @param UserFactory            $userFactory
     * @param EntityManagerInterface $em
     */
    public function __construct(UserFactory $userFactory, EntityManagerInterface $em)
    {
        $this->userFactory = $userFactory;
        $this->em = $em;
    }

    /**
     * @param ReaderInterface $reader
     * @param FieldsMatches   $matches
     *
     * @return ImportStatistics
     */
    public function import(ReaderInterface $reader, FieldsMatches $matches): ImportStatistics
    {
        $successful = 0;
        $failed = 0;

        $this->startTimer();
        $this->em->beginTransaction();

        foreach ($reader->getData() as $userData) {
            try {
                $user = $this->userFactory->create($userData, $matches);
                $this->em->persist($user);
                ++$successful;
            } catch (UserException $e) {
                ++$failed;
            }
        }

        $this->em->flush();
        $this->em->commit();
        $duration = $this->stopTimer();

        $statistics = new ImportStatistics(
            $duration,
            $successful,
            $failed
        );

        return $statistics;
    }

    private function startTimer()
    {
        $this->timer = new Stopwatch();
        $this->timer->start('importer');
    }

    /**
     * @return int
     */
    private function stopTimer(): int
    {
        $event = $this->timer->stop('importer');
        unset($this->timer);

        return $event->getDuration();
    }
}
