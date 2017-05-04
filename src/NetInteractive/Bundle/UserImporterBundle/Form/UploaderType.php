<?php

declare(strict_types=1);

namespace NetInteractive\Bundle\UserImporterBundle\Form;

use NetInteractive\Bundle\UserImporterBundle\Validator\Constraints\CsvUserFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class UploaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('csvFile', FileType::class, [
            'constraints' => [
                new File([
                    'mimeTypes' => [
                        'application/vnd.ms-excel',
                        'text/plain',
                        'text/csv',
                        'text/x-csv',
                    ],
                    'maxSize' => '8Mi',
                ]),
                new CsvUserFile([
                    'minFields' => 2,
                    'maxFields' => 50,
                ]),
            ],
        ]);

        $builder->add('upload', SubmitType::class);
    }
}
