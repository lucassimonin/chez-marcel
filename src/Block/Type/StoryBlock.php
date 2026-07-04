<?php

namespace App\Block\Type;

use App\Block\AbstractBlockType;
use App\Form\MediaPickerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class StoryBlock extends AbstractBlockType
{
    public function getKey(): string
    {
        return 'story';
    }

    public function getLabel(): string
    {
        return 'Histoire (visuel + texte + chiffres)';
    }

    public function getDescription(): string
    {
        return 'Deux colonnes : une image à gauche, un texte et des chiffres clés à droite.';
    }

    public function buildForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('anchor', TextType::class, ['label' => 'Ancre HTML', 'required' => false, 'help' => 'Ex: histoire → lien #histoire'])
            ->add('kicker', TextType::class, ['label' => 'Surtitre', 'required' => false])
            ->add('title', TextType::class, ['label' => 'Titre', 'required' => false])
            ->add('text', TextareaType::class, ['label' => 'Texte', 'required' => false, 'attr' => ['rows' => 5], 'help' => 'Un paragraphe par ligne vide. HTML simple autorisé (<em>, <strong>).'])
            ->add('image', MediaPickerType::class, ['label' => 'Image', 'required' => false, 'help' => 'Vide = dégradé chaud de remplacement'])
            ->add('image_alt', TextType::class, ['label' => 'Texte alternatif de l\'image', 'required' => false])
            ->add('image_tag', TextType::class, ['label' => 'Étiquette sur l\'image', 'required' => false, 'help' => 'Ex: Photo — terrasse en bois'])
            ->add('stats', TextareaType::class, ['label' => 'Chiffres clés (une ligne : valeur | libellé)', 'required' => false, 'attr' => ['rows' => 3], 'help' => 'Ex: 7j/7 | Ouverture']);
    }

    public function getDefaultData(): array
    {
        return [
            'anchor' => 'histoire',
            'kicker' => 'Notre histoire',
            'title' => 'Un lieu de vie et de partage',
            'text' => '',
            'image' => '',
            'image_alt' => '',
            'image_tag' => '',
            'stats' => "7j/7 | Ouverture\n2 | Restaurant & Glacier\n100% | Fait maison",
        ];
    }
}
