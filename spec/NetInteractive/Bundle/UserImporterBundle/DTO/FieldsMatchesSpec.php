<?php

namespace spec\NetInteractive\Bundle\UserImporterBundle\DTO;

use NetInteractive\Bundle\UserImporterBundle\DTO\FieldsMatches;
use PhpSpec\ObjectBehavior;

class FieldsMatchesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(FieldsMatches::class);
    }

    public function it_should_dynamically_store_field_values()
    {
        // Given
        $this->hello = 'world';

        // When
        $this->hello

        // Then
            ->shouldReturn('world');
    }

    public function it_should_allow_to_use_attributes_with_white_spaces()
    {
        // Given
        $this->{'Hello world'} = 'yep';

        // When
        $this->{'Hello world'}

        // Then
            ->shouldReturn('yep');
    }

    public function it_should_be_able_to_return_at_once_all_stored_values()
    {
        // Given
        $this->hello = 'world';
        $this->how = 'you doing?';
        $this->{'how old'} = 'are you?';

        // When
        $this->getMatchings()

        // Then
            ->shouldReturn([
                'hello' => 'world',
                'how' => 'you doing?',
                'how old' => 'are you?',
            ]);
    }
}
