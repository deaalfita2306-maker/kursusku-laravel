<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthRoleSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function createCourse(string $status = 'open', string $code = 'WEB-101'): Course
    {
        return Course::create([
            'code' => $code,
            'name' => 'Pemrograman Web Dasar',
            'description' => 'Belajar dasar web.',
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-12-20',
            'status' => $status,
        ]);
    }

    public function test_guest_can_access_public_pages_and_courses(): void
    {
        $openCourse = $this->createCourse('open', 'OPEN-01');
        $draftCourse = $this->createCourse('draft', 'DRAFT-01');

        $this->get(route('home'))->assertStatus(200);
        $this->get(route('info'))->assertStatus(200);
        $this->get(route('courses.index'))->assertStatus(200)->assertSee('OPEN-01')->assertDontSee('DRAFT-01');
        $this->get(route('courses.show', $openCourse))->assertStatus(200);
        $this->get(route('courses.show', $draftCourse))->assertStatus(403);
        $this->get(route('login'))->assertStatus(200);
        $this->get(route('register'))->assertStatus(200);
        $this->get(route('password.request'))->assertStatus(200);
    }

    public function test_guest_cannot_access_protected_routes(): void
    {
        $course = $this->createCourse('open', 'WEB-01');

        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('profile.show'))->assertRedirect(route('login'));
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
        $this->get(route('courses.create'))->assertRedirect(route('login'));
        $this->post(route('courses.store'), [])->assertRedirect(route('login'));
        $this->get(route('courses.edit', $course))->assertRedirect(route('login'));
        $this->put(route('courses.update', $course), [])->assertRedirect(route('login'));
        $this->delete(route('courses.destroy', $course))->assertRedirect(route('login'));
        $this->post(route('courses.enroll', $course))->assertRedirect(route('login'));
        $this->get(route('enrollments.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_register_with_hashed_password_and_cannot_escalate_role(): void
    {
        $response = $this->post(route('register.process'), [
            'name' => 'Siti Aminah',
            'username' => 'sitiaminah',
            'email' => 'siti@example.com',
            'phone' => '081234567890',
            'password' => 'SecretPass123!',
            'password_confirmation' => 'SecretPass123!',
            'role' => 'admin', // Percobaan manipulasi request
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'siti@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('user', $user->role);
        $this->assertNotSame('SecretPass123!', $user->password);
        $this->assertTrue(Hash::check('SecretPass123!', $user->password));
    }

    public function test_user_can_login_with_email_or_username_remember_me_and_logout(): void
    {
        $user = User::factory()->create([
            'username' => 'budi_user',
            'email' => 'budi@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'user',
        ]);

        // Login dengan username + remember me
        $response = $this->post(route('login.process'), [
            'login' => 'budi_user',
            'password' => 'Password123!',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->remember_token);

        // Logout
        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();

        // Login dengan email
        $this->post(route('login.process'), [
            'login' => 'budi@example.com',
            'password' => 'Password123!',
        ])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_update_own_profile_and_password_without_role_escalation(): void
    {
        $user = User::factory()->create([
            'username' => 'andi_user',
            'email' => 'andi@example.com',
            'password' => Hash::make('OldPass123!'),
            'role' => 'user',
        ]);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Andi Wijaya',
                'username' => 'andi_wijaya',
                'email' => 'andi.baru@example.com',
                'phone' => '081999888777',
                'role' => 'admin', // Percobaan manipulasi request
            ])
            ->assertRedirect(route('profile.show'));

        $user->refresh();
        $this->assertSame('Andi Wijaya', $user->name);
        $this->assertSame('user', $user->role);

        // Update password
        $this->actingAs($user)
            ->put(route('profile.password'), [
                'current_password' => 'OldPass123!',
                'password' => 'NewPass123!',
                'password_confirmation' => 'NewPass123!',
            ])
            ->assertRedirect(route('profile.show'));

        $this->assertTrue(Hash::check('NewPass123!', $user->fresh()->password));
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'password' => Hash::make('OldPass123!'),
            'role' => 'user',
        ]);

        $this->post(route('password.email'), ['email' => 'reset@example.com'])
            ->assertSessionHas('status');

        $token = Password::broker()->createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'reset@example.com',
            'password' => 'ResetPass123!',
            'password_confirmation' => 'ResetPass123!',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('ResetPass123!', $user->fresh()->password));
    }

    public function test_user_can_enroll_in_open_course_but_cannot_access_admin_or_crud(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $course = $this->createCourse('open', 'LAR-101');

        // User bisa enroll
        $this->actingAs($user)
            ->post(route('courses.enroll', $course))
            ->assertRedirect(route('courses.show', $course));

        $participant = Participant::where('email', $user->email)->first();
        $this->assertNotNull($participant);
        $this->assertDatabaseHas('enrollments', [
            'participant_id' => $participant->id,
            'course_id' => $course->id,
        ]);

        // User tidak boleh mengakses dashboard admin, manajemen user, atau CRUD kursus
        $this->actingAs($user)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($user)->get(route('admin.users.index'))->assertStatus(403);
        $this->actingAs($user)->get(route('courses.create'))->assertStatus(403);
        $this->actingAs($user)->post(route('courses.store'), [
            'code' => 'HACK-01',
            'name' => 'Hack Course',
            'fee' => 1000,
            'quota' => 10,
            'start_date' => '2026-12-30',
            'status' => 'open',
        ])->assertStatus(403);
        $this->actingAs($user)->get(route('courses.edit', $course))->assertStatus(403);
        $this->actingAs($user)->put(route('courses.update', $course), [])->assertStatus(403);
        $this->actingAs($user)->delete(route('courses.destroy', $course))->assertStatus(403);
    }

    public function test_admin_can_manage_dashboard_courses_and_users(): void
    {
        $admin = User::factory()->admin()->create([
            'username' => 'superadmin',
            'password' => Hash::make('AdminSecret123!'),
        ]);
        $targetUser = User::factory()->create(['role' => 'user']);

        // Login admin
        $this->post(route('login.process'), [
            'login' => 'superadmin',
            'password' => 'AdminSecret123!',
        ])->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertStatus(200);

        // CRUD Course
        $this->actingAs($admin)->post(route('courses.store'), [
            'code' => 'ADM-101',
            'name' => 'Administrasi Server',
            'description' => 'Materi server Linux.',
            'fee' => 750000,
            'quota' => 15,
            'start_date' => '2026-12-25',
            'status' => 'open',
        ])->assertRedirect(route('courses.index'));

        $course = Course::where('code', 'ADM-101')->firstOrFail();

        $this->actingAs($admin)->put(route('courses.update', $course), [
            'code' => 'ADM-101',
            'name' => 'Administrasi Server Lanjut',
            'fee' => 850000,
            'quota' => 20,
            'start_date' => '2026-12-25',
            'status' => 'closed',
        ])->assertRedirect(route('courses.index'));

        $this->assertSame('Administrasi Server Lanjut', $course->fresh()->name);

        $this->actingAs($admin)->delete(route('courses.destroy', $course))
            ->assertRedirect(route('courses.index'));
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);

        // CRUD User
        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Pengguna Baru',
            'username' => 'penggunabaru',
            'email' => 'baru@example.com',
            'role' => 'user',
            'is_active' => '1',
            'password' => 'UserPass123!',
            'password_confirmation' => 'UserPass123!',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['email' => 'baru@example.com']);

        // Admin tidak bisa menghapus diri sendiri
        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))
            ->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        // Admin bisa menghapus user lain
        $this->actingAs($admin)->delete(route('admin.users.destroy', $targetUser))
            ->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }
}
