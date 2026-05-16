<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSeoSchemaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_about_page_exposes_schema_org_structured_data()
    {
        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('application/ld+json', false);
        $response->assertSee('AboutPage', false);
        $response->assertSee('BreadcrumbList', false);
    }

    /** @test */
    public function a_blog_post_page_exposes_blogposting_schema()
    {
        $author = User::factory()->create();

        $post = BlogPost::create([
            'title' => 'Impact local et leadership',
            'excerpt' => 'Un article test pour verifier le schema BlogPosting.',
            'content' => 'Contenu detaille de l article pour le referencement structure.',
            'author_id' => $author->id,
            'is_published' => true,
            'published_at' => now()->subDay(),
            'meta_description' => 'Description SEO du billet de blog test.',
        ]);

        $response = $this->get(route('blog.show', $post->slug));

        $response->assertOk();
        $response->assertSee('application/ld+json', false);
        $response->assertSee('BlogPosting', false);
        $response->assertSee('BreadcrumbList', false);
    }
}
