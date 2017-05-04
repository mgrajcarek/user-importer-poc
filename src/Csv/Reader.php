<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Csv;

use NetInteractive\Bundle\UserImporterBundle\Csv\Exception\FileException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\File\File;

class Reader implements ReaderInterface
{
    /**
     * @var File
     */
    private $file;

    /**
     * @param File $file
     */
    private function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * @param File $file
     *
     * @return Reader
     */
    public static function load(File $file)
    {
        $reader = new self($file);

        return $reader;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        $handler = fopen($this->file->getPathname(), 'r');
        $headers = $this->extractHeaders($handler);
        fclose($handler);

        return $headers;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $handler = fopen($this->file->getPathname(), 'r');
        $headers = $this->extractHeaders($handler);

        $data = [];
        while (($rowData = $this->extractRowData($handler)) !== false) {
            $data[] = array_combine($headers, $rowData);
        }

        fclose($handler);

        return $data;
    }

    /**
     * @param resource $handler
     *
     * @return array
     */
    private function extractHeaders($handler): array
    {
        fseek($handler, 0);
        $headers = $this->extractRowData($handler);

        if ($headers) {
            $headers = array_map(function ($header) {
                return preg_replace('/[^0-9a-zA-Z_\-:]/', '', $header);
            }, $headers);

            $headers = array_filter($headers, function ($header) {
                return !empty($header);
            });
        }

        if (empty($headers)) {
            throw FileException::headersNotFound();
        }

        return array_map(function ($header) {
            return Container::camelize($header);
        }, $headers);
    }

    /**
     * @param resource $handler
     *
     * @return array|null
     */
    private function extractRowData($handler)
    {
        return fgetcsv($handler, 0, ',', '"');
    }
}
