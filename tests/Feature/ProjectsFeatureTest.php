<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Services\FacebookImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectsFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_homepage_shows_only_featured_visible_projects()
    {
        $this->mock(FacebookImportService::class, function ($mock) {
            $mock->shouldReceive('fetchEvents')->once()->with(3)->andReturn([]);
        });

        Event::create([
            'type' => 'project',
            'title' => 'Projet visible',
            'description' => 'Ce projet doit apparaitre sur la page d accueil.',
            'order' => 1,
            'is_featured' => true,
            'is_active' => true,
            'show_on_website' => true,
        ]);

        Event::create([
            'type' => 'project',
            'title' => 'Projet cache',
            'description' => 'Ce projet ne doit pas apparaitre.',
            'order' => 2,
            'is_featured' => true,
            'is_active' => true,
            'show_on_website' => false,
        ]);

        Event::create([
            'type' => 'meeting',
            'title' => 'Reunion interne',
            'description' => 'Cet element ne doit pas apparaitre dans la section projets.',
            'order' => 3,
            'is_featured' => true,
            'is_active' => true,
            'show_on_website' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Projet visible');
        $response->assertSee(__('website.home.our_projects'));
        $response->assertDontSee('Projet cache');
        $response->assertDontSee('Reunion interne');
    }

    /** @test */
    public function an_admin_can_create_a_project_from_the_dashboard()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.projects.store'), [
            'title' => 'Nouveau projet dashboard',
            'description' => 'Projet ajoute depuis le dashboard.',
            'icon' => 'M9 12l2 2 4-4',
            'icon_color' => 'blue',
            'order' => 4,
            'is_featured' => '1',
            'is_active' => '1',
            'show_on_website' => '1',
        ]);

        $response->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseHas('events', [
            'type' => 'project',
            'title' => 'Nouveau projet dashboard',
            'show_on_website' => true,
        ]);

        $indexResponse = $this->actingAs($admin)->get(route('admin.projects.index'));

        $indexResponse->assertOk();
        $indexResponse->assertSee(__('website.admin.projects.title'));
        $indexResponse->assertSee('Nouveau projet dashboard');
    }
}
