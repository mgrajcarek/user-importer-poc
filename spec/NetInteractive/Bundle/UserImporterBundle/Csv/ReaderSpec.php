<?php

namespace spec\NetInteractive\Bundle\UserImporterBundle\Csv;

use NetInteractive\Bundle\UserImporterBundle\Csv\Exception\FileException;
use NetInteractive\Bundle\UserImporterBundle\Csv\Reader;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\File\File;

class ReaderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Reader::class);
    }

    public function let()
    {
        // Given
        $testFile = new File(__DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'test.csv');
        $this->beConstructedThrough('load', [$testFile]);
    }

    public function it_should_read_file_headers()
    {
        // When
        $this->getHeaders()

        // Then
            ->shouldReturn([
            'Number', 'Gender', 'StreetAddress', 'BreakTitle',
        ]);
    }

    public function it_should_read_file_data()
    {
        // When
        $this->getData()

        // Then
            ->shouldReturn([
                ['Number' => '1', 'Gender' => 'male', 'StreetAddress' => 'ul. Polnych Kwiatów 71', 'BreakTitle' => ''],
                ['Number' => '2', 'Gender' => 'female', 'StreetAddress' => 'ul. Taczaka Stanisława 78', 'BreakTitle' => ''],
            ]);
    }

    public function it_should_immediately_stop_headers_extraction_if_they_were_not_found_in_a_file()
    {
        // Given
        $testFile = new File(__DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'test_empty.csv');
        $this->beConstructedThrough('load', [$testFile]);

        // Then, When
        $this->shouldThrow(FileException::headersNotFound())->duringGetHeaders();
    }

    public function it_should_immediately_stop_data_extraction_if_headers_were_not_found_in_a_file()
    {
        // Given
        $testFile = new File(__DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'test_empty.csv');
        $this->beConstructedThrough('load', [$testFile]);

        // Then, When
        $this->shouldThrow(FileException::headersNotFound())->duringGetData();
    }
}
