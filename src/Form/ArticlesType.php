<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('image', FileType::class, [
                'label' => 'Image article',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024m', // base : 1024k
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide sous format PNG ou JPEG',
                    ])
                ],
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'rows' => 15,
                    'style' => 'resize: none;'
                ],
            ])
            ->add('categories', EntityType::class, [
                // looks for choices from this entity
                'class' => Categories::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',

                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
