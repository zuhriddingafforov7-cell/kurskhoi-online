<?php

namespace Tests\Unit;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Module;
use App\Models\User;
use App\Services\CourseCompletionService;
use App\Support\ModelMeta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseCompletionAndMetaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        ModelMeta::flush();
    }

    protected function tearDown(): void
    {
        ModelMeta::flush();
        parent::tearDown();
    }

    public function test_model_meta_store_and_course_level_inference(): void
    {
        $this->assertSame('beginner', ModelMeta::inferCourseLevel('Асосҳои HTML ва CSS'));
        $this->assertSame('intermediate', ModelMeta::inferCourseLevel('Таҳияи барнома бо Laravel ва API'));
        $this->assertSame('advanced', ModelMeta::inferCourseLevel('Барномасозии объектгаро ва амният'));

        ModelMeta::set('course', 42, 'level', 'advanced');
        $this->assertSame('advanced', ModelMeta::inferCourseLevel('Асосҳои HTML', 42));
        $this->assertSame('Пешрафта', ModelMeta::levelLabel('advanced'));
        $this->assertSame('Миёна', ModelMeta::levelLabel('intermediate'));
        $this->assertSame('Ибтидоӣ', ModelMeta::levelLabel('beginner'));

        ModelMeta::set('course', 42, 'level', null);
        $this->assertNull(ModelMeta::get('course', 42, 'level'));
    }

    public function test_lesson_to_embed_url_transforms_youtube_and_vimeo_links(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            Lesson::toEmbedUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        );
        $this->assertSame(
            'https://www.youtube.com/embed/abc123XYZ',
            Lesson::toEmbedUrl('https://youtu.be/abc123XYZ')
        );
        $this->assertSame(
            'https://www.youtube.com/embed/shortVid99',
            Lesson::toEmbedUrl('https://youtube.com/shorts/shortVid99')
        );
        $this->assertSame(
            'https://player.vimeo.com/video/123456789',
            Lesson::toEmbedUrl('https://vimeo.com/123456789')
        );
        $this->assertNull(Lesson::toEmbedUrl(null));
    }

    public function test_lesson_is_unlocked_for_respects_sequential_order_across_modules(): void
    {
        $author = User::factory()->create(['role' => 'author']);
        $student = User::factory()->create(['role' => 'student']);
        $cat = Category::create(['title' => 'IT', 'slug' => 'it']);
        $course = Course::create([
            'title'       => 'Курси пайдарпай',
            'description' => 'Тавсиф',
            'category_id' => $cat->id,
            'author_id'   => $author->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);

        $mod1 = Module::create(['course_id' => $course->id, 'title' => 'Модули 1', 'order_index' => 1]);
        $mod2 = Module::create(['course_id' => $course->id, 'title' => 'Модули 2', 'order_index' => 2]);

        $l1 = Lesson::create(['module_id' => $mod1->id, 'title' => 'Дарси 1', 'content_type' => 'text', 'order_index' => 1]);
        $l2 = Lesson::create(['module_id' => $mod1->id, 'title' => 'Дарси 2', 'content_type' => 'text', 'order_index' => 2]);
        $l3 = Lesson::create(['module_id' => $mod2->id, 'title' => 'Дарси 3', 'content_type' => 'text', 'order_index' => 1]);

        $this->assertTrue($l1->isUnlockedFor($student->id));
        $this->assertFalse($l2->isUnlockedFor($student->id));
        $this->assertFalse($l3->isUnlockedFor($student->id));

        LessonProgress::create(['user_id' => $student->id, 'lesson_id' => $l1->id, 'is_completed' => true, 'completed_at' => now()]);

        $this->assertTrue($l2->isUnlockedFor($student->id));
        $this->assertFalse($l3->isUnlockedFor($student->id));

        LessonProgress::create(['user_id' => $student->id, 'lesson_id' => $l2->id, 'is_completed' => true, 'completed_at' => now()]);

        $this->assertTrue($l3->isUnlockedFor($student->id));
    }

    public function test_course_completion_service_requires_all_lessons_and_approved_assignments(): void
    {
        $author = User::factory()->create(['role' => 'author']);
        $student = User::factory()->create(['role' => 'student']);
        $cat = Category::create(['title' => 'Backend', 'slug' => 'backend']);
        $course = Course::create([
            'title'       => 'Курси пурра',
            'description' => 'Тавсиф',
            'category_id' => $cat->id,
            'author_id'   => $author->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);

        $module = Module::create(['course_id' => $course->id, 'title' => 'Модули 1', 'order_index' => 1]);
        $lesson = Lesson::create(['module_id' => $module->id, 'title' => 'Дарси 1', 'content_type' => 'text', 'order_index' => 1]);
        $assignment = Assignment::create([
            'lesson_id'   => $lesson->id,
            'title'       => 'Вазифаи 1',
            'description' => 'Шарт',
            'max_score'   => 100,
        ]);

        $enrollment = Enrollment::create([
            'user_id'     => $student->id,
            'course_id'   => $course->id,
            'enrolled_at' => now(),
            'status'      => 'active',
        ]);

        LessonProgress::create(['user_id' => $student->id, 'lesson_id' => $lesson->id, 'is_completed' => true, 'completed_at' => now()]);

        $service = new CourseCompletionService();

        // Без одобренного задания курс не должен завершаться
        $this->assertFalse($service->checkAndComplete($enrollment));
        $this->assertSame('active', $enrollment->fresh()->status);
        $this->assertSame(0, Certificate::count());

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id'       => $student->id,
            'answer_text'   => 'Кори аввал',
            'status'        => 'submitted',
        ]);

        $this->assertFalse($service->checkAndComplete($enrollment));

        // После одобрения курс завершается и выдается сертификат
        $submission->update(['status' => 'approved', 'score' => 90]);
        $this->assertTrue($service->checkAndComplete($enrollment));
        $this->assertSame('completed', $enrollment->fresh()->status);

        $cert = Certificate::where('enrollment_id', $enrollment->id)->first();
        $this->assertNotNull($cert);
        $this->assertStringStartsWith('BIYO-', $cert->certificate_code);
    }
}
