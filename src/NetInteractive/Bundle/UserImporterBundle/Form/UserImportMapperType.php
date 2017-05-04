<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Form;

use NetInteractive\Bundle\UserImporterBundle\DTO\FieldsMatches;
use NetInteractive\Bundle\UserImporterBundle\Type\TargetFieldChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserImportMapperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['csv_fields'] as $field) {
            $builder->add($field, TargetFieldChoiceType::class, ['required' => false]);
        }

        $builder->add('import', SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csv_fields' => [],
            'data_class' => FieldsMatches::class,
        ]);
    }
}
