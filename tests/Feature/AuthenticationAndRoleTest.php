<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthenticationAndRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Бақайдгирии муваффақи Student.
     */
    public function test_student_can_register_successfully(): void
    {
        $response = $this->post('/register', [
            'name' => 'Рустам Ҷӯраев',
            'email' => 'rustam@biyomuz.tj',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/student/dashboard');

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'name' => 'Рустам Ҷӯраев',
            'email' => 'rustam@biyomuz.tj',
            'role' => 'student',
        ]);
    }

    /**
     * 2. Рад кардани Email-и такрорӣ.
     */
    public function test_registration_fails_with_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'mavjud@biyomuz.tj',
        ]);

        $response = $this->post('/register', [
            'name' => 'Корбари Нав',
            'email' => 'mavjud@biyomuz.tj',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * 3. Рад кардани маълумоти нодурусти Register (майдонҳои холӣ, пароли кӯтоҳ, номувофиқати тасдиқ).
     */
    public function test_registration_fails_with_invalid_data(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }

    /**
     * 4. Набудани имконияти таъини нақши Admin ҳангоми Register (муҳофизат аз mass-assignment ва ҳакерӣ).
     */
    public function test_user_cannot_register_as_admin_via_role_tampering(): void
    {
        $response = $this->post('/register', [
            'name' => 'Кӯшиши Ҳакер',
            'email' => 'hacker@biyomuz.tj',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin', // Кӯшиши фиристодани нақши admin
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/student/dashboard');

        // Нақш бояд қатъиян 'student' бошад, на 'admin'
        $this->assertDatabaseHas('users', [
            'email' => 'hacker@biyomuz.tj',
            'role' => 'student',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'hacker@biyomuz.tj',
            'role' => 'admin',
        ]);
    }

    /**
     * 5. Login бо пароли дуруст ва равонашавӣ ба Dashboard-и мувофиқи нақш.
     */
    public function test_user_can_login_with_correct_credentials(): void
    {
        $student = User::factory()->create([
            'email' => 'donishju@biyomuz.tj',
            'password' => Hash::make('sirri-donishju123'),
            'role' => 'student',
        ]);

        $response = $this->post('/login', [
            'email' => 'donishju@biyomuz.tj',
            'password' => 'sirri-donishju123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/student/dashboard');
        $this->assertAuthenticatedAs($student);
    }

    /**
     * 6. Рад кардани Login бо пароли нодуруст.
     */
    public function test_user_cannot_login_with_incorrect_password(): void
    {
        User::factory()->create([
            'email' => 'user@biyomuz.tj',
            'password' => Hash::make('password-haqiqi'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user@biyomuz.tj',
            'password' => 'password-nodurust',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * 7. Logout ва бекор шудани сессия.
     */
    public function test_user_can_logout_and_session_is_invalidated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * 8. Guest ба масирҳои ҳифзшуда дастрасӣ надорад ва ба login равона мешавад.
     */
    public function test_guest_cannot_access_protected_routes_and_is_redirected_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/author/dashboard')->assertRedirect('/login');
        $this->get('/student/dashboard')->assertRedirect('/login');
    }

    /**
     * 9. Student ба Admin Dashboard ва Author Dashboard дастрасӣ надорад (403 Forbidden).
     */
    public function test_student_cannot_access_admin_or_author_dashboard(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $this->actingAs($student)->get('/admin/dashboard')->assertStatus(403);
        $this->actingAs($student)->get('/author/dashboard')->assertStatus(403);
    }

    /**
     * 10. Author ба масирҳои Author дастрасӣ дорад ва ба Admin дастрасӣ надорад.
     */
    public function test_author_can_access_author_routes_but_not_admin_dashboard(): void
    {
        $author = User::factory()->author()->create();

        $this->actingAs($author)->get('/author/dashboard')->assertStatus(200);
        $this->actingAs($author)->get('/author/courses')->assertStatus(200);
        $this->actingAs($author)->get('/admin/dashboard')->assertStatus(403);
    }

    /**
     * 11. Admin ба масири Admin Dashboard дастрасӣ дорад ва ба масири Student дастрасӣ надорад.
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($admin)->get('/student/dashboard')->assertStatus(403);
    }

    /**
     * 12. Санҷиши Forgot Password ва Reset Password.
     */
    public function test_forgot_password_and_reset_password_flow(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'barqaror@biyomuz.tj',
            'password' => Hash::make('kuhna-password-123'),
        ]);

        // 1. Ирсоли дархости Forgot Password
        $response = $this->post('/forgot-password', [
            'email' => 'barqaror@biyomuz.tj',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('status');

        // Санҷиши фиристода шудани огоҳиномаи барқарорсозии парол
        $resetToken = null;
        Notification::assertSentTo(
            $user,
            ResetPassword::class,
            function (ResetPassword $notification) use (&$resetToken) {
                $resetToken = $notification->token;
                return true;
            }
        );

        $this->assertNotNull($resetToken, 'Токени барқарорсозии парол бояд тавлид шавад.');

        // 2. Намоиши саҳифаи Reset Password
        $pageResponse = $this->get('/reset-password/'.$resetToken.'?email='.urlencode($user->email));
        $pageResponse->assertStatus(200);

        // 3. Ворид кардани пароли нав
        $resetResponse = $this->post('/reset-password', [
            'token' => $resetToken,
            'email' => 'barqaror@biyomuz.tj',
            'password' => 'navi-password-456',
            'password_confirmation' => 'navi-password-456',
        ]);

        $resetResponse->assertStatus(302);
        $resetResponse->assertRedirect('/login');
        $resetResponse->assertSessionHas('status');

        // Санҷиши он ки парол дар база нав шудааст
        $this->assertTrue(
            Hash::check('navi-password-456', $user->fresh()->password),
            'Пароли нави истифодабаранда бояд мувофиқат кунад.'
        );
    }
}
