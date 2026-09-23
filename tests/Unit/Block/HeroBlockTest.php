<?php

namespace App\Tests\Unit\Block;

use App\Block\Type\HeroBlock;
use PHPUnit\Framework\TestCase;

class HeroBlockTest extends TestCase
{
    public function testDefautContientLesDeuxImagesDuCarrousel(): void
    {
        $defauts = (new HeroBlock())->getDefaultData();

        // La deuxième image est facultative : elle déclenche le fondu
        // entre les deux photos de fond quand elle est renseignée.
        $this->assertArrayHasKey('image', $defauts);
        $this->assertArrayHasKey('image_2', $defauts);
        $this->assertArrayHasKey('image_2_alt', $defauts);
        $this->assertSame('', $defauts['image_2']);
    }

    public function testLesDefautsNeCassentPasLesBlocsExistants(): void
    {
        $bloc = new HeroBlock();

        // Un hero enregistré avant l'ajout du carrousel n'a pas image_2 :
        // la fusion avec les défauts doit rester sans effet visible.
        $ancien = ['title' => 'Chez Marcel', 'image' => '/uploads/media/a.webp'];
        $fusionne = array_merge($bloc->getDefaultData(), $ancien);

        $this->assertSame('Chez Marcel', $fusionne['title']);
        $this->assertSame('/uploads/media/a.webp', $fusionne['image']);
        $this->assertSame('', $fusionne['image_2'], 'Sans deuxième image, pas de carrousel.');
    }
}
