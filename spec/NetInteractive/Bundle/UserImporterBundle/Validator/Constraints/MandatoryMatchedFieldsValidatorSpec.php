<?php

namespace spec\NetInteractive\Bundle\UserImporterBundle\Validator\Constraints;

use NetInteractive\Bundle\UserImporterBundle\DTO\FieldsMatches;
use NetInteractive\Bundle\UserImporterBundle\Validator\Constraints\MandatoryMatchedFields;
use NetInteractive\Bundle\UserImporterBundle\Validator\Constraints\MandatoryMatchedFieldsValidator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class MandatoryMatchedFieldsValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(MandatoryMatchedFieldsValidator::class);
    }

    public function let(
        MandatoryMatchedFields $constraint,
        ExecutionContextInterface $executionContext,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        $this->initialize($executionContext);

        $executionContext->buildViolation($constraint->message)
            ->willReturn($constraintViolationBuilder);
    }

    public function it_should_fail_if_at_least_one_of_mandatory_fields_where_not_matched(
        MandatoryMatchedFields $constraint,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        // Given
        $constraint->fields = ['username', 'givenName', 'lastName'];

        $matches = new FieldsMatches();
        $matches->Username = 'username';
        $matches->Password = 'password';

        // Then
        $constraintViolationBuilder->setParameter('%missing%', 'givenName, lastName')
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder->addViolation()
            ->shouldBeCalled();

        // When
        $this->validate($matches, $constraint);
    }

    public function it_should_pass_if_all_mandatory_fields_has_been_matched(
        MandatoryMatchedFields $constraint,
        ConstraintViolationBuilderInterface $constraintViolationBuilder
    ) {
        // Given
        $constraint->fields = ['username', 'givenName'];

        $matches = new FieldsMatches();
        $matches->Username = 'username';
        $matches->Password = 'password';
        $matches->GivenName = 'givenName';

        // Then
        $constraintViolationBuilder->addViolation()
            ->shouldNotBeCalled();

        // When
        $this->validate($matches, $constraint);
    }
}
