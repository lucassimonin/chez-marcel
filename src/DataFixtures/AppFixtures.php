<?php

namespace App\DataFixtures;

use App\Entity\Block;
use App\Entity\NavigationItem;
use App\Entity\Page;
use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\Setting;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Site de démonstration : CHEZ MARCEL (restaurant & glacier, Palavas-les-Flots).
 * Intégration de la maquette client.
 * Connexion admin : admin@agence.fr / admin
 */
class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUser($manager);
        $this->loadSettings($manager);
        $this->loadNavigation($manager);
        $this->loadHomepage($manager);
        $this->loadLegalPage($manager);
        $this->loadPosts($manager);

        $manager->flush();
    }

    private function loadUser(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@agence.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($user, 'admin'));
        $manager->persist($user);
    }

    private function loadSettings(ObjectManager $manager): void
    {
        $settings = [
            'site_name' => 'Chez Marcel',
            'tagline' => 'Restaurant & glacier — Palavas-les-Flots',
            'logo_url' => '',
            'contact_email' => 'contact@chezmarcel-palavas.fr',
            'mailer_from' => 'no-reply@chezmarcel-palavas.fr',
            'phone' => '04 67 XX XX XX',
            'address' => 'Bord de plage, Palavas-les-Flots',
            'footer_text' => 'Chez Marcel — Palavas-les-Flots',
            // ID de démo : rend la barre cookies visible dès l'installation.
            // Remplacer par le vrai ID GA4 du client (ou vider pour désactiver).
            'analytics_id' => 'G-DEMO000000',
        ];

        foreach ($settings as $key => $value) {
            $manager->persist((new Setting())->setKey($key)->setValue($value));
        }
    }

    private function loadNavigation(ObjectManager $manager): void
    {
        $header = [
            ["L'histoire", '#histoire', false],
            ['La carte', '#carte', false],
            ['Galerie', '#galerie', false],
            ['Contact', '#contact', false],
            ['Réserver', '#contact', true],
        ];

        foreach ($header as $position => [$label, $url, $isButton]) {
            $manager->persist((new NavigationItem())
                ->setLabel($label)->setUrl($url)
                ->setLocation(NavigationItem::LOCATION_HEADER)
                ->setPosition($position)->setIsButton($isButton));
        }

        // Menu de la version anglaise (réduite)
        $headerEn = [
            ['The menu', '#carte', false],
            ['Contact', '#contact', false],
            ['Book a table', '#contact', true],
        ];

        foreach ($headerEn as $position => [$label, $url, $isButton]) {
            $manager->persist((new NavigationItem())
                ->setLabel($label)->setUrl($url)->setLocale('en')
                ->setLocation(NavigationItem::LOCATION_HEADER)
                ->setPosition($position)->setIsButton($isButton));
        }

        $manager->persist((new NavigationItem())
            ->setLabel('Actualités')->setUrl('/actualites')
            ->setLocation(NavigationItem::LOCATION_FOOTER)->setPosition(0));
        $manager->persist((new NavigationItem())
            ->setLabel('Mentions légales')->setUrl('/mentions-legales')
            ->setLocation(NavigationItem::LOCATION_FOOTER)->setPosition(1));
    }

    private function loadHomepage(ObjectManager $manager): void
    {
        $page = new Page();
        $page->setTitle('Chez Marcel — Palavas-les-Flots')
            ->setSlug('accueil')
            ->setIsHomepage(true)
            ->setStatus(Page::STATUS_PUBLISHED)
            ->setMetaTitle('Chez Marcel — Restaurant & glacier à Palavas-les-Flots')
            ->setMetaDescription('Chez Marcel, restaurant et glacier au bord de la plage de Palavas-les-Flots. Cuisine généreuse, produits de qualité, terrasse face à la mer. Ouvert 7j/7, midi et soir.')
            ->setStructuredData(json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Restaurant',
                'name' => 'Chez Marcel',
                'servesCuisine' => 'Cuisine du marché, poissons, glaces artisanales',
                'telephone' => '+33467000000',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Bord de plage',
                    'addressLocality' => 'Palavas-les-Flots',
                    'postalCode' => '34250',
                    'addressCountry' => 'FR',
                ],
                'openingHours' => 'Mo-Su 12:00-14:30,19:00-22:30',
                'url' => 'https://www.chezmarcel-palavas.fr',
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        $blocks = [
            ['hero', [
                'kicker' => 'Palavas-les-Flots',
                'title' => 'Chez Marcel',
                'subtitle' => '',
                'tagline' => '',
                'text' => 'Cuisine généreuse, produits de qualité, terrasse face à la mer. Une adresse à vivre en famille ou entre amis.',
                'image' => '',
                'image_alt' => '',
                'primary_label' => 'Réserver une table',
                'primary_link' => '#contact',
                'secondary_label' => 'Voir la carte',
                'secondary_link' => '#carte',
                'ribbon' => "Ouvert 7j / 7\nRestaurant & Glacier\nFace à la plage",
            ]],
            ['story', [
                'anchor' => 'histoire',
                'kicker' => 'Notre histoire',
                'title' => 'Un lieu de vie et de partage',
                'text' => "Chez Marcel repose sur une idée simple : offrir une cuisine généreuse, préparée avec des produits de qualité, dans une atmosphère conviviale. Ici, on vient autant pour bien manger que pour passer un moment agréable.\nLe restaurant s'appuie sur le savoir-faire d'un chef expérimenté et d'une équipe passionnée par la restauration, pour garantir un accueil chaleureux et un service soigné, midi et soir.",
                'image' => '',
                'image_alt' => '',
                'image_tag' => 'Photo — terrasse en bois',
                'stats' => "7j/7 | Ouverture\n2 | Restaurant & Glacier\n100% | Fait maison",
            ]],
            ['highlights', [
                'anchor' => '',
                'items' => "waves | Terrasse face à la mer | Une grande terrasse en bois pour profiter du soleil de Palavas, midi et soir.\nutensils | Cuisine généreuse | Des produits de qualité et des plats préparés avec soin, dans le respect des saisons.\nice-cream-cone | Glacier attenant | Une adresse gourmande avec son glacier, pour prolonger le moment en douceur.\nheart-handshake | Accueil chaleureux | Une équipe passionnée, présente pour que chaque visite soit un bon moment.",
            ]],
            ['menu_list', [
                'anchor' => 'carte',
                'kicker' => 'La carte',
                'title' => 'Quelques incontournables',
                'intro' => 'Exemples de présentation — à remplacer par votre carte définitive et vos prix.',
                'categories' => [
                    [
                        'title' => '',
                        'items' => "Tellines poêlées, persillade | 14 €\nTielle façon Marcel | 12 €\nLoup grillé, fenouil confit | 24 €\nSouris d'agneau confite | 22 €\nTarte tatin maison | 8 €\nGlace & sorbets Chez Marcel | 6 €",
                        'note' => 'La carte évolue au fil du marché et des arrivages de la pêche.',
                    ],
                ],
            ]],
            ['gallery', [
                'anchor' => 'galerie',
                'kicker' => 'Galerie',
                'title' => 'Un aperçu du lieu',
                'images' => " | La terrasse\n | La salle\n | Un plat du jour\n | Vue sur mer\n | Le glacier",
            ]],
            ['cta_banner', [
                'anchor' => '',
                'stamp_top' => 'Palavas-les-Flots',
                'stamp_big' => 'Chez Marcel',
                'stamp_bottom' => 'Bord de mer',
                'title' => 'Une table vous attend',
                'text' => 'Réservez en ligne en quelques secondes, ou appelez-nous directement pour les groupes.',
                'button_label' => 'Réserver une table',
                'button_link' => '#contact',
            ]],
            ['contact', [
                'anchor' => 'contact',
                'kicker' => 'Le lieu',
                'title' => 'Retrouvez-nous à Palavas',
                'place_name' => 'Chez Marcel',
                'address' => "Bord de plage\n34250 Palavas-les-Flots",
                'access_note' => 'Face à la mer — terrasse ombragée, accès direct depuis la plage.',
                'hours' => 'Ouvert 7j/7, midi et soir',
                'phone' => '04 67 XX XX XX',
                'phone_href' => '+33467000000',
                'call_label' => 'Appeler pour réserver',
                'form_title' => 'Réserver une table',
                'form_intro' => 'Demande de réservation ou privatisation — réponse rapide.',
                'subjects' => "Réservation table\nGroupe / événement\nAutre question",
                'recipient' => '',
                'map_embed' => 'https://maps.google.com/maps?q=43.5289,3.9303&z=15&output=embed',
            ]],
        ];

        foreach ($blocks as $position => [$type, $data]) {
            $block = new Block();
            $block->setType($type)->setData($data)->setPosition($position);
            $page->addBlock($block);
        }

        $manager->persist($page);
        $this->loadEnglishHomepage($manager, $page);
    }

    /** Version anglaise (réduite) de la homepage — démontre hreflang + sélecteur de langue */
    private function loadEnglishHomepage(ObjectManager $manager, Page $frenchHomepage): void
    {
        $page = new Page();
        $page->setTitle('Chez Marcel — Palavas-les-Flots (seaside restaurant)')
            ->setSlug('home')
            ->setLocale('en')
            ->setTranslationGroup($frenchHomepage->getTranslationGroup())
            ->setIsHomepage(true)
            ->setStatus(Page::STATUS_PUBLISHED)
            ->setMetaTitle('Chez Marcel — Seaside restaurant & ice cream in Palavas-les-Flots')
            ->setMetaDescription('Chez Marcel — restaurant and ice cream parlour by the beach in Palavas-les-Flots. Generous cooking, quality produce, terrace facing the sea. Open 7 days a week.');

        $blocks = [
            ['hero', [
                'kicker' => 'Palavas-les-Flots',
                'title' => 'Chez Marcel',
                'subtitle' => '',
                'tagline' => '',
                'text' => 'Generous cooking, quality produce, a terrace facing the sea. A place to enjoy with family or friends.',
                'image' => '',
                'image_alt' => '',
                'primary_label' => 'Book a table',
                'primary_link' => '#contact',
                'secondary_label' => 'See the menu',
                'secondary_link' => '#carte',
                'ribbon' => "Open 7 days a week\nRestaurant & Ice cream\nRight on the beach",
            ]],
            ['contact', [
                'anchor' => 'contact',
                'kicker' => 'The place',
                'title' => 'Find us in Palavas',
                'place_name' => 'Chez Marcel',
                'address' => "By the beach\n34250 Palavas-les-Flots, France",
                'access_note' => 'Facing the sea — shaded terrace, direct access from the beach.',
                'hours' => 'Open 7/7, lunch & dinner',
                'phone' => '+33 4 67 XX XX XX',
                'phone_href' => '+33467000000',
                'call_label' => 'Call to book',
                'form_title' => 'Book a table',
                'form_intro' => 'Booking or private events — quick reply.',
                'subjects' => "Table booking\nGroup / event\nOther",
                'recipient' => '',
                'map_embed' => 'https://maps.google.com/maps?q=43.5289,3.9303&z=15&output=embed',
            ]],
        ];

        foreach ($blocks as $position => [$type, $data]) {
            $block = new Block();
            $block->setType($type)->setData($data)->setPosition($position);
            $page->addBlock($block);
        }

        $manager->persist($page);
    }

    private function loadLegalPage(ObjectManager $manager): void
    {
        $page = new Page();
        $page->setTitle('Mentions légales')
            ->setSlug('mentions-legales')
            ->setStatus(Page::STATUS_PUBLISHED)
            ->setNoindex(true)
            ->setMetaTitle('Mentions légales — Chez Marcel');

        $block = new Block();
        $block->setType('rich_text')->setPosition(0)->setData([
            'kicker' => '',
            'title' => 'Mentions légales',
            'content' => "<h2>Éditeur du site</h2>\n<p>CHEZ MARCEL — Bord de plage, 34250 Palavas-les-Flots.</p>\n<h2>Hébergement</h2>\n<p>À compléter.</p>\n<h2>Données personnelles</h2>\n<p>Les informations transmises via le formulaire de contact sont utilisées uniquement pour répondre à votre demande.</p>",
        ]);
        $page->addBlock($block);

        $manager->persist($page);
        $this->loadNotFoundPage($manager);
    }

    /** Page 404 éditable : rendue automatiquement sur les URL introuvables */
    private function loadNotFoundPage(ObjectManager $manager): void
    {
        $page = new Page();
        $page->setTitle('Page introuvable')
            ->setSlug('erreur-404')
            ->setStatus(Page::STATUS_PUBLISHED)
            ->setNoindex(true)
            ->setMetaTitle('Page introuvable — Chez Marcel');

        $block = new Block();
        $block->setType('rich_text')->setPosition(0)->setData([
            'kicker' => 'Erreur 404',
            'title' => 'Vous vous êtes égaré en chemin',
            'content' => "<p>Cette page n'existe pas (ou plus). On reprend la direction de la plage et on retrouve son chemin.</p>\n<p><a href=\"/\">← Retour à l'accueil</a></p>",
        ]);
        $page->addBlock($block);

        $manager->persist($page);
    }

    private function loadPosts(ObjectManager $manager): void
    {
        $events = (new PostCategory())->setName('Événements')->setSlug('evenements');
        $kitchen = (new PostCategory())->setName('Côté cuisine')->setSlug('cote-cuisine');
        $manager->persist($events);
        $manager->persist($kitchen);

        $posts = [
            [
                'title' => 'La pêche du jour arrive directement au restaurant',
                'slug' => 'peche-du-jour',
                'category' => $kitchen,
                'excerpt' => 'Loup, dorade, tellines : selon les arrivages, la pêche locale s\'invite chaque jour à la carte.',
                'content' => "<p>Notre chef compose la carte au fil des retours de pêche. Selon la marée et la saison, poissons entiers grillés, tellines de l'étang et coquillages font leur apparition à l'ardoise.</p>\n<h2>Au plus près du produit</h2>\n<ul>\n<li>Pêche locale, livrée le matin</li>\n<li>Cuisson minute, assaisonnement simple</li>\n<li>Suggestions du jour selon les arrivages</li>\n</ul>",
                'cover' => '',
                'coverAlt' => 'Poisson grillé, spécialité de Chez Marcel',
                'publishedAt' => '-3 days',
            ],
            [
                'title' => 'Soirées moules-frites face à la mer',
                'slug' => 'soirees-moules-frites',
                'category' => $events,
                'excerpt' => 'Chaque vendredi soir, moules-frites à volonté sur la terrasse, au coucher du soleil.',
                'content' => "<p>Rendez-vous convivial de l'été : nos soirées moules-frites reviennent tous les vendredis sur la terrasse face à la plage.</p>\n<h2>Infos pratiques</h2>\n<ul>\n<li>Tous les vendredis dès 19h</li>\n<li>Réservation conseillée via le formulaire</li>\n<li>Ambiance décontractée, vue sur mer</li>\n</ul>",
                'cover' => '',
                'coverAlt' => 'Terrasse de Chez Marcel au coucher du soleil',
                'publishedAt' => '-10 days',
            ],
            [
                'title' => 'Bientôt : la carte des glaces maison',
                'slug' => 'carte-glaces-maison',
                'category' => $kitchen,
                'excerpt' => 'Notre glacier prépare une nouvelle carte de parfums de saison, faits maison.',
                'content' => "<p>Le glacier attenant s'apprête à dévoiler sa carte de l'été : parfums de saison, sorbets plein fruit et créations maison. À découvrir très bientôt.</p>",
                'cover' => '',
                'coverAlt' => '',
                'publishedAt' => null, // brouillon : exemple de prévisualisation
            ],
        ];

        foreach ($posts as $data) {
            $post = (new Post())
                ->setTitle($data['title'])
                ->setSlug($data['slug'])
                ->setCategory($data['category'])
                ->setExcerpt($data['excerpt'])
                ->setContent($data['content'])
                ->setCoverImage($data['cover'] ?: null)
                ->setCoverAlt($data['coverAlt'] ?: null)
                ->setMetaDescription($data['excerpt']);

            if ($data['publishedAt']) {
                $post->setPublishedAt(new \DateTimeImmutable($data['publishedAt']));
            }

            $manager->persist($post);
        }
    }
}
