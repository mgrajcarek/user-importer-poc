<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TargetFieldChoiceType extends AbstractType
{
    private $targetFields = [
        'username',
        'givenName',
        'surname',
        'number',
        'gender',
        'nameSet',
        'title',
        'middleInitial',
        'streetAddress',
        'city',
        'state',
        'zipCode',
        'country',
        'emailAddress',
        'password',
        'browserUserAgent',
    ];

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => array_combine($this->targetFields, $this->targetFields),
            'placeholder' => 'Choose an option',
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
