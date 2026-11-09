<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Муаллифи воридшуда метавонад курс пешниҳод кунад ва саҳифаи таъинот дастрас аст.
     */
    public function test_authenticated_author_can_submit_course(): void
    {
        $author = User::factory()->author()->create();

        $response = $this->actingAs($author)->post('/author/courses', [
            'title' => 'Асосҳои барномасозӣ бо PHP ва Laravel',
            'description' => 'Курси амалӣ оид ба таҳияи сомонаҳои муосир',
        ]);

        // Санҷиши ҷавоби 302 ва масири бозгашт ба /author/courses
        $response->assertStatus(302);
        $response->assertRedirect('/author/courses');

        // Санҷиши он ки саҳифаи таъинот (/author/courses) воқеан дастрас буда, рамзи 200 медиҳад
        $destinationResponse = $this->actingAs($author)->get('/author/courses');
        $destinationResponse->assertStatus(200);
    }

    /**
     * 2. Курси пешниҳодшуда бо ҳолати pending сабт мешавад.
     */
    public function test_submitted_course_is_saved_with_pending_status(): void
    {
        $author = User::factory()->author()->create();

        $this->actingAs($author)->post('/author/courses', [
            'title' => 'Frontend бо Tailwind CSS ва JavaScript',
            'description' => 'Тарроҳии интерфейсҳои мутобиқшаванда',
        ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'Frontend бо Tailwind CSS ва JavaScript',
            'author_id' => $author->id,
            'status' => 'pending',
        ]);
    }

    /**
     * 3. Донишҷӯ наметавонад курс эҷод кунад ва ҷавоби 403 мегирад.
     */
    public function test_student_cannot_submit_course_and_receives_403(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $response = $this->actingAs($student)->post('/author/courses', [
            'title' => 'Кӯшиши эҷод аз ҷониби донишҷӯ',
            'description' => 'Ин амал бояд манъ карда шавад',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('courses', [
            'title' => 'Кӯшиши эҷод аз ҷониби донишҷӯ',
        ]);
    }

    /**
     * 4. Маълумоти нодуруст ё нопурра қабул намешавад ва хатои валидатсия (422) медиҳад.
     */
    public function test_invalid_or_incomplete_data_returns_validation_error(): void
    {
        $author = User::factory()->author()->create();

        // Дархости JSON барои гирифтани рамзи 422 Unprocessable Entity
        $response = $this->actingAs($author)->postJson('/author/courses', [
            'title' => '', // Майдони ҳатмӣ холӣ аст
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * 5. Корбари вориднашуда ба масири муҳофизатшудаи POST дастрасӣ надорад ва ба login равона мешавад.
     */
    public function test_unauthenticated_user_cannot_access_protected_route(): void
    {
        $response = $this->post('/author/courses', [
            'title' => 'Курси бе иҷозат',
        ]);

        // Корбари вориднашуда ба саҳифаи login равона карда мешавад
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest();
        $this->assertDatabaseCount('courses', 0);
    }

    /**
     * 6. Донишҷӯ ба феҳристи курсҳои муаллиф (GET /author/courses) дастрасӣ надорад ва 403 мегирад.
     */
    public function test_student_cannot_access_author_courses_index(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $response = $this->actingAs($student)->get('/author/courses');
        $response->assertStatus(403);
    }

    /**
     * 7. Корбари вориднашуда ҳангоми муроҷиат ба GET /author/courses ба /login равона мешавад.
     */
    public function test_unauthenticated_user_redirected_to_login_from_author_courses_index(): void
    {
        $response = $this->get('/author/courses');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
