<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\DTO;

class ImportStatistics implements \Serializable
{
    /**
     * @var int
     */
    public $duration;

    /**
     * @var int
     */
    public $successful;

    /**
     * @var int
     */
    public $failed;

    /**
     * @param int $duration   (ms)
     * @param int $successful
     * @param int $failed
     */
    public function __construct(int $duration, int $successful, int $failed)
    {
        $this->duration = $duration;
        $this->successful = $successful;
        $this->failed = $failed;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->duration,
            $this->successful,
            $this->failed,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        list(
            $this->duration,
            $this->successful,
            $this->failed
        ) = $data;
    }
}
