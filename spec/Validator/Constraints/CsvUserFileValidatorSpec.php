<?php

namespace spec\NetInteractive\Bundle\UserImporterBundle\Validator\Constraints;

use NetInteractive\Bundle\UserImporterBundle\Validator\Constraints\CsvUserFile;
use NetInteractive\Bundle\UserImporterBundle\Validator\Constraints\CsvUserFileValidator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class CsvUserFileValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CsvUserFileValidator::class);
    }

    public function let(
        ExecutionContextInterface $executionContext
    ) {
        $this->initialize($executionContext);
    }

    public function it_should_invalidate_files_without_headers(
        CsvUserFile $constraint,
        ExecutionContextInterface $executionContext,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        // Given
        $file = new UploadedFile(
            __DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'no_headers.csv',
            'users.csv'
        );

        // Then
        $executionContext->buildViolation($constraint->headersNotFoundMessage)
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder->addViolation()
            ->shouldBeCalled();

        // When
        $this->validate($file, $constraint);
    }

    public function it_should_invalidate_files_without_minimum_number_of_fields(
        CsvUserFile $constraint,
        ExecutionContextInterface $executionContext,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        // Given
        $file = new UploadedFile(
            __DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'not_enough_fields.csv',
            'users.csv'
        );

        // Then
        $executionContext->buildViolation($constraint->notEnoughFieldsMessage)
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder->setParameter('%expected%', 2)
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder->setParameter('%found%', 1)
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder->addViolation()
            ->shouldBeCalled();

        // When
        $this->validate($file, $constraint);
    }

    public function it_should_invalidate_files_with_too_many_fields(
        CsvUserFile $constraint,
        ExecutionContextInterface $executionContext,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        // Given
        $file = new UploadedFile(
            __DIR__.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'too_many_fields.csv',
            'users.csv'
        );
        $constraint->maxFields = 3;

        // Then
        $executionContext->buildViolation($constraint->tooManyFieldsMessage)
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder->setParameter('%expected%', 3)
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder->setParameter('%found%', 4)
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder->addViolation()
            ->shouldBeCalled();

        // When
        $this->validate($file, $constraint);
    }
}
