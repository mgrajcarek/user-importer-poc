<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\DTO;

class FieldsMatches
{
    /**
     * @var array
     */
    private $matchings = [];

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->matchings[$key] ?? null;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value): void
    {
        $this->matchings[$key] = $value;
    }

    /**
     * @return array
     */
    public function getMatchings(): array
    {
        return $this->matchings;
    }
}
