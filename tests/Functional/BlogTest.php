<?php

namespace App\Tests\Functional;

use App\Entity\Post;
use App\Entity\Redirect;

class BlogTest extends DatabaseWebTestCase
{
    public function testBlogIndexListsPublishedPosts(): void
    {
        $this->client->request('GET', '/actualites');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Actualités');
        $this->assertSelectorTextContains('body', 'Soirées moules-frites face à la mer');
        // Le brouillon n'apparaît pas
        $this->assertSelectorTextNotContains('body', 'la carte des glaces maison');
    }

    public function testCategoryFilter(): void
    {
        $this->client->request('GET', '/actualites?categorie=cote-cuisine');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'La pêche du jour');
        $this->assertSelectorTextNotContains('body', 'moules-frites');
    }

    public function testPostPageWithSeo(): void
    {
        $this->client->request('GET', '/actualites/peche-du-jour');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'pêche du jour');
        $this->assertSelectorExists('meta[property="og:type"][content="article"]');
        $this->assertSelectorExists('script[type="application/ld+json"]');
    }

    public function testDraftPostIs404ForVisitors(): void
    {
        $this->client->request('GET', '/actualites/carte-glaces-maison');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testRssFeed(): void
    {
        $this->client->request('GET', '/actualites/rss.xml');

        $this->assertResponseIsSuccessful();
        $content = (string) $this->client->getResponse()->getContent();
        $this->assertStringContainsString('<rss', $content);
        $this->assertStringContainsString('moules-frites', $content);
        $this->assertStringNotContainsString('glaces maison', $content);
    }

    public function testSitemapIncludesPosts(): void
    {
        $this->client->request('GET', '/sitemap.xml');

        $this->assertResponseIsSuccessful();
        $content = (string) $this->client->getResponse()->getContent();
        $this->assertStringContainsString('/actualites', $content);
        $this->assertStringContainsString('peche-du-jour', $content);
    }

    public function testPostSlugChangeCreatesRedirect(): void
    {
        $post = $this->em->getRepository(Post::class)->findOneBy(['slug' => 'peche-du-jour']);
        $post->setSlug('nouvelle-peche-du-jour');
        $this->em->flush();

        $redirect = $this->em->getRepository(Redirect::class)->findOneBy(['source' => '/actualites/peche-du-jour']);
        $this->assertNotNull($redirect);
        $this->assertSame('/actualites/nouvelle-peche-du-jour', $redirect->getTarget());
    }
}
