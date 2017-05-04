<?php

namespace spec\NetInteractive\Bundle\UserImporterBundle\Validator\Constraints;

use NetInteractive\Bundle\UserImporterBundle\DTO\FieldsMatches;
use NetInteractive\Bundle\UserImporterBundle\Validator\Constraints\UniquelyMatchedFields;
use NetInteractive\Bundle\UserImporterBundle\Validator\Constraints\UniquelyMatchedFieldsValidator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class UniquelyMatchedFieldsValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UniquelyMatchedFieldsValidator::class);
    }

    public function let(
        UniquelyMatchedFields $constraint,
        ExecutionContextInterface $executionContext,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $this->initialize($executionContext);

        $executionContext->buildViolation($constraint->message)
            ->willReturn($constraintViolationBuilder);
    }

    public function it_should_not_pass_if_multiple_source_values_has_the_same_target_table_attributes(
        UniquelyMatchedFields $constraint,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        // Given
        $matches = new FieldsMatches();
        $matches->Username = 'username';
        $matches->Password = 'password';
        $matches->GivenName = 'username';
        $matches->Number = 'number';
        $matches->SthElse = 'password';

        // Then
        $constraintViolationBuilder->setParameter('%repeated%', 'username, password')
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder->addViolation()
            ->shouldBeCalled();

        // When
        $this->validate($matches, $constraint);
    }

    public function it_should_pass_if_all_target_attributes_are_used_only_once(
        UniquelyMatchedFields $constraint,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        // Given
        $matches = new FieldsMatches();
        $matches->Username = 'username';
        $matches->Password = 'password';
        $matches->Number = 'number';

        // Then
        $constraintViolationBuilder->addViolation()
            ->shouldNotBeCalled();

        // When
        $this->validate($matches, $constraint);
    }
}
