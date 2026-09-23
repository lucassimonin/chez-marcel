<?php

namespace App\Block\Type;

use App\Block\AbstractBlockType;
use App\Form\MediaPickerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class HeroBlock extends AbstractBlockType
{
    public function getKey(): string
    {
        return 'hero';
    }

    public function getLabel(): string
    {
        return 'Hero plein écran';
    }

    public function getDescription(): string
    {
        return 'Image de fond, titre, accroche et deux boutons d\'action.';
    }

    public function buildForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('kicker', TextType::class, ['label' => 'Surtitre', 'required' => false, 'help' => 'Petite ligne au-dessus du titre (ex: Marché du Lez · Montpellier)'])
            ->add('title', TextType::class, ['label' => 'Titre', 'required' => false])
            ->add('logo', MediaPickerType::class, ['label' => 'Logo (remplace le titre)', 'required' => false, 'help' => 'Si renseigné, le logo s\'affiche à la place du titre texte. Il est automatiquement passé en blanc sur la photo de fond.'])
            ->add('subtitle', TextType::class, ['label' => 'Sous-titre', 'required' => false, 'help' => 'Ligne en majuscules sous le titre'])
            ->add('tagline', TextType::class, ['label' => 'Accroche (italique)', 'required' => false])
            ->add('text', TextareaType::class, ['label' => 'Texte', 'required' => false, 'attr' => ['rows' => 3]])
            ->add('image', MediaPickerType::class, ['label' => 'Image de fond', 'required' => false, 'help' => 'Choisissez dans la bibliothèque ou collez une URL'])
            ->add('image_alt', TextType::class, ['label' => 'Texte alternatif de l\'image', 'required' => false])
            ->add('image_2', MediaPickerType::class, ['label' => 'Deuxième image de fond (facultatif)', 'required' => false, 'help' => 'Si renseignée, les deux photos alternent en fondu'])
            ->add('image_2_alt', TextType::class, ['label' => 'Texte alternatif de la deuxième image', 'required' => false])
            ->add('primary_label', TextType::class, ['label' => 'Bouton principal — libellé', 'required' => false])
            ->add('primary_link', TextType::class, ['label' => 'Bouton principal — lien ou ancre', 'required' => false, 'help' => 'Ex: #menu ou /contact'])
            ->add('secondary_label', TextType::class, ['label' => 'Bouton secondaire — libellé', 'required' => false])
            ->add('secondary_link', TextType::class, ['label' => 'Bouton secondaire — lien ou ancre', 'required' => false])
            ->add('ribbon', TextareaType::class, ['label' => 'Ruban de mentions', 'required' => false, 'attr' => ['rows' => 3], 'help' => 'Une mention par ligne (ex: Ouvert 7j/7). Affichées en bas du hero.']);
    }

    public function getDefaultData(): array
    {
        return [
            'kicker' => 'Surtitre',
            'title' => 'Titre principal',
            'logo' => '',
            'subtitle' => '',
            'tagline' => '',
            'text' => '',
            'image' => '',
            'image_alt' => '',
            'image_2' => '',
            'image_2_alt' => '',
            'primary_label' => '',
            'primary_link' => '',
            'secondary_label' => '',
            'secondary_link' => '',
            'ribbon' => '',
        ];
    }
}
