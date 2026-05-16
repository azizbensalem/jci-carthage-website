<?php

namespace Tests\Feature;

use App\Models\President;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_active_presidents_on_the_presidents_page()
    {
        President::create([
            'name' => 'Amina First',
            'photo' => 'presidents/amina-first.jpg',
            'presidency_year' => '2024',
            'order' => 2,
            'is_active' => true,
        ]);

        President::create([
            'name' => 'Aziz Ben Salem',
            'photo' => 'presidents/aziz-ben-salem.jpg',
            'presidency_year' => '2026',
            'order' => 1,
            'is_active' => true,
        ]);

        President::create([
            'name' => 'Hidden President',
            'photo' => 'presidents/hidden-president.jpg',
            'presidency_year' => '2025',
            'order' => 2,
            'is_active' => false,
        ]);

        $response = $this->get(route('presidents'));

        $response->assertOk();
        $response->assertSeeInOrder(['Amina First', 'Aziz Ben Salem']);
        $response->assertSee('Président 2024');
        $response->assertSee('Président 2026');
        $response->assertDontSee('Hidden President');
    }

    /** @test */
    public function the_about_page_still_loads_without_the_presidents_grid()
    {
        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee(__('website.nav.about'));
        $response->assertSee(route('presidents'), false);
    }

    /** @test */
    public function an_admin_can_open_the_presidents_dashboard()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.presidents.index'));

        $response->assertOk();
        $response->assertSee(__('website.admin.presidents.title'));
    }
}
