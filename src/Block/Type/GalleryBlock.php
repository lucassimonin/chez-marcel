<?php

namespace App\Block\Type;

use App\Block\AbstractBlockType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GalleryBlock extends AbstractBlockType
{
    public function getKey(): string
    {
        return 'gallery';
    }

    public function getLabel(): string
    {
        return 'Galerie (grille d\'images)';
    }

    public function getDescription(): string
    {
        return 'Mosaïque d\'images (la première en grand). Une image par ligne : URL | légende.';
    }

    public function buildForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('anchor', TextType::class, ['label' => 'Ancre HTML', 'required' => false, 'help' => 'Ex: galerie → lien #galerie'])
            ->add('kicker', TextType::class, ['label' => 'Surtitre', 'required' => false])
            ->add('title', TextType::class, ['label' => 'Titre', 'required' => false])
            ->add('images', TextareaType::class, [
                'label' => 'Images (une ligne : URL | légende)',
                'required' => false,
                'attr' => ['rows' => 6],
                'help' => 'Laissez l\'URL vide pour un aperçu coloré temporaire. Ex: https://…/terrasse.jpg | La terrasse',
            ]);
    }

    public function getDefaultData(): array
    {
        return [
            'anchor' => 'galerie',
            'kicker' => 'Galerie',
            'title' => 'Un aperçu du lieu',
            'images' => " | La terrasse\n | La salle\n | Un plat du jour\n | Vue sur mer\n | Le glacier",
        ];
    }
}
