<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function test_home_route_is_accessible_and_named_home(): void
    {
        $this->assertTrue(Route::has('home'));
        $this->assertEquals(url('/'), route('home'));

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_home_view_receives_required_data(): void
    {
        $response = $this->get(route('home'));

        $response->assertViewHas('siteName', 'KursusKu');
        $response->assertViewHas('tagline');
        $response->assertViewHas('featured');
        $response->assertViewHas('advantages');
        $response->assertViewHas('facilities');
        $response->assertViewHas('steps');

        $featured = $response->viewData('featured');
        $this->assertIsArray($featured);
        $this->assertCount(6, $featured);

        foreach ($featured as $course) {
            $this->assertArrayHasKey('code', $course);
            $this->assertArrayHasKey('name', $course);
            $this->assertArrayHasKey('description', $course);
            $this->assertArrayHasKey('price', $course);
            $this->assertArrayHasKey('category', $course);
            $this->assertArrayHasKey('participants', $course);
            $this->assertArrayHasKey('capacity', $course);
            $this->assertArrayHasKey('status', $course);
            $this->assertArrayHasKey('seats_left', $course);
        }
    }

    public function test_home_view_renders_courses_from_controller(): void
    {
        $response = $this->get(route('home'));

        $response->assertSee('KursusKu');
        $response->assertSee('Web Development');
        $response->assertSee('UI/UX Design');
        $response->assertSee('Laravel Dasar');
        $response->assertSee('Data Analysis');
        $response->assertSee('Digital Marketing');
        $response->assertSee('Desain Grafis');

        // Check codes
        $response->assertSee('WEB-01');
        $response->assertSee('UIUX-02');
        $response->assertSee('LAR-03');
        $response->assertSee('DATA-04');
        $response->assertSee('MKT-05');
        $response->assertSee('DSG-06');

        // Check prices formatted
        $response->assertSee('Rp 750.000');
        $response->assertSee('Rp 850.000');
        $response->assertSee('Rp 500.000');

        // Check asset references
        $response->assertSee(asset('css/style.css'));
        $response->assertSee(asset('images/hero-kursus.jpg'));

        // Check semantic and responsive meta tags
        $response->assertSee('name="viewport"', false);
    }

    public function test_physical_assets_exist_on_filesystem(): void
    {
        $this->assertFileExists(public_path('css/style.css'));
        $this->assertFileExists(public_path('images/hero-kursus.jpg'));
    }

    public function test_no_git_repository_exists(): void
    {
        $this->assertDirectoryDoesNotExist(base_path('.git'));
    }

    public function test_old_prototype_directory_is_untouched(): void
    {
        $this->assertFileExists('D:/laragon/www/kursusku-prototype/index.php');
        $this->assertFileExists('D:/laragon/www/kursusku-prototype/Data.php');
        $this->assertFileExists('D:/laragon/www/kursusku-prototype/assets/images/hero-kursus.jpg');
    }
}
