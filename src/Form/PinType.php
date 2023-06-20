<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class)
        ->add('description', TextareaType::class)
        ->add('imageFile', VichImageType::class, [
        'label' => 'Image (JPEG or PNG file)',
        'required' => false,
        'allow_delete' => true,
        'delete_label' => 'Supprimer',
        'download_label' => 'Télécharger',
        'download_uri' => true,
        // 'image_uri' => true,
        // 'imagine_pattern' => 'squared_thumbnail_medium',
        // 'asset_helper' => true,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
