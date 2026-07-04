<?php

namespace App\Block\Type;

use App\Block\AbstractBlockType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class HighlightsBlock extends AbstractBlockType
{
    public function getKey(): string
    {
        return 'highlights';
    }

    public function getLabel(): string
    {
        return 'Atouts (grille d\'icônes)';
    }

    public function getDescription(): string
    {
        return 'Grille d\'atouts : une icône, un titre et un court texte par colonne.';
    }

    public function buildForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('anchor', TextType::class, ['label' => 'Ancre HTML', 'required' => false])
            ->add('items', TextareaType::class, [
                'label' => 'Atouts (une ligne : icône | titre | texte)',
                'required' => false,
                'attr' => ['rows' => 5],
                'help' => 'Icônes Lucide, ex: waves | Terrasse face à la mer | Une grande terrasse en bois.',
            ]);
    }

    public function getDefaultData(): array
    {
        return [
            'anchor' => '',
            'items' => "waves | Terrasse face à la mer | Une grande terrasse en bois pour profiter du soleil, midi et soir.\nutensils | Cuisine généreuse | Des produits de qualité et des plats préparés avec soin, au fil des saisons.\nice-cream-cone | Glacier attenant | Une adresse gourmande avec son glacier, pour prolonger le moment.\nheart-handshake | Accueil chaleureux | Une équipe passionnée, présente pour que chaque visite soit un bon moment.",
        ];
    }
}
