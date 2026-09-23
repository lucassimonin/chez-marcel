<?php

namespace App\Block\Type;

use App\Block\AbstractBlockType;
use App\Form\MediaPickerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CtaBannerBlock extends AbstractBlockType
{
    public function getKey(): string
    {
        return 'cta_banner';
    }

    public function getLabel(): string
    {
        return 'Bandeau d\'appel à l\'action (tampon)';
    }

    public function getDescription(): string
    {
        return 'Bandeau plein largeur en dégradé, avec un tampon rond, un titre et un bouton.';
    }

    public function buildForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('anchor', TextType::class, ['label' => 'Ancre HTML', 'required' => false])
            ->add('logo', MediaPickerType::class, ['label' => 'Logo (remplace le tampon)', 'required' => false, 'help' => 'Si renseigné, le logo s\'affiche à la place du tampon rond. Il est automatiquement passé en blanc sur le fond coloré.'])
            ->add('logo_alt', TextType::class, ['label' => 'Texte alternatif du logo', 'required' => false])
            ->add('stamp_top', TextType::class, ['label' => 'Tampon — ligne du haut', 'required' => false, 'help' => 'Ex: Palavas-les-Flots'])
            ->add('stamp_big', TextType::class, ['label' => 'Tampon — mot central', 'required' => false, 'help' => 'Ex: Chez Marcel'])
            ->add('stamp_bottom', TextType::class, ['label' => 'Tampon — ligne du bas', 'required' => false, 'help' => 'Ex: Depuis 2026'])
            ->add('title', TextType::class, ['label' => 'Titre', 'required' => false])
            ->add('text', TextareaType::class, ['label' => 'Texte', 'required' => false, 'attr' => ['rows' => 2]])
            ->add('button_label', TextType::class, ['label' => 'Bouton — libellé', 'required' => false])
            ->add('button_link', TextType::class, ['label' => 'Bouton — lien ou ancre', 'required' => false]);
    }

    public function getDefaultData(): array
    {
        return [
            'anchor' => '',
            'logo' => '',
            'logo_alt' => '',
            'stamp_top' => 'Palavas-les-Flots',
            'stamp_big' => 'Chez Marcel',
            'stamp_bottom' => 'Depuis 2026',
            'title' => 'Une table vous attend',
            'text' => 'Réservez en quelques secondes, ou appelez-nous directement pour les groupes.',
            'button_label' => 'Réserver une table',
            'button_link' => '#contact',
        ];
    }
}
