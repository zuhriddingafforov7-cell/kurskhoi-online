<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonMaterial;
use App\Models\LessonProgress;
use App\Models\Module;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DomainRequirementsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $author;
    protected User $student;
    protected Category $category;
    protected Course $course;
    protected Module $module;
    protected Lesson $lesson1;
    protected Lesson $lesson2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Маъмури Система',
            'email' => 'admin@test.tj',
            'role' => 'admin',
        ]);

        $this->author = User::factory()->create([
            'name' => 'Аҳмад Муаллиф',
            'email' => 'author@test.tj',
            'role' => 'author',
        ]);

        $this->student = User::factory()->create([
            'name' => 'Фарҳод Донишҷӯ',
            'email' => 'student@test.tj',
            'role' => 'student',
        ]);

        $this->category = Category::create([
            'title' => 'Веб-барномасозӣ',
            'slug' => 'web-programming',
            'description' => 'Курсҳои барномасозии веб',
        ]);

        $this->course = Course::create([
            'title' => 'Асосҳои Laravel',
            'description' => 'Омӯзиши фреймворки Laravel аз сифр',
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => Course::STATUS_PUBLISHED,
        ]);

        $this->module = Module::create([
            'course_id' => $this->course->id,
            'title' => 'Модули 1. Муқаддима',
            'order_index' => 1,
        ]);

        $this->lesson1 = Lesson::create([
            'module_id' => $this->module->id,
            'title' => 'Дарси 1. Насби Laravel',
            'content_type' => 'text',
            'body_text' => 'Матни назариявии дарси якум...',
            'order_index' => 1,
        ]);

        $this->lesson2 = Lesson::create([
            'module_id' => $this->module->id,
            'title' => 'Дарси 2. Роҳхатҳо ва контроллерҳо',
            'content_type' => 'text',
            'body_text' => 'Матни назариявии дарси дуюм...',
            'order_index' => 2,
        ]);
    }

    /**
     * 1. Номнависӣ ба курси нашршуда.
     */
    public function test_student_can_enroll_in_published_course(): void
    {
        $response = $this->actingAs($this->student)->post("/student/courses/{$this->course->id}/enroll");

        $response->assertStatus(302);
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);
    }

    /**
     * 2. Пешгирии номнависии такрорӣ.
     */
    public function test_cannot_enroll_twice_in_same_course(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->student)->post("/student/courses/{$this->course->id}/enroll");

        $response->assertStatus(302);
        $this->assertEquals(1, Enrollment::where('user_id', $this->student->id)->where('course_id', $this->course->id)->count());
    }

    /**
     * 3. Пайдарпайии дарсҳо: Дарси 1 кушода, дарси 2 баста то анҷоми дарси 1.
     */
    public function test_sequential_lesson_access(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        // Дарси 1 дастрас аст
        $response1 = $this->actingAs($this->student)->get("/student/lessons/{$this->lesson1->id}");
        $response1->assertStatus(200);

        // Дарси 2 дастрас нест (403) то анҷоми дарси 1
        $response2 = $this->actingAs($this->student)->get("/student/lessons/{$this->lesson2->id}");
        $response2->assertStatus(403);
    }

    /**
     * 4. Анҷом додани дарси 1 ва боз шудани дарси 2.
     */
    public function test_completing_lesson_unlocks_next_lesson(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        $completeResp = $this->actingAs($this->student)->post("/student/lessons/{$this->lesson1->id}/complete");
        $completeResp->assertStatus(302);

        $this->assertDatabaseHas('lesson_progress', [
            'user_id' => $this->student->id,
            'lesson_id' => $this->lesson1->id,
            'is_completed' => true,
        ]);

        // Акнун дарси 2 дастрас аст
        $response2 = $this->actingAs($this->student)->get("/student/lessons/{$this->lesson2->id}");
        $response2->assertStatus(200);
    }

    /**
     * 5. Супориши амалӣ ва баҳодиҳии муаллиф.
     */
    public function test_assignment_submission_and_grading(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        $assignment = Assignment::create([
            'lesson_id' => $this->lesson1->id,
            'title' => 'Супориши амалии насб',
            'description' => 'Скриншоти насби бомуваффақиятро фиристед',
            'max_score' => 100,
        ]);

        // Донишҷӯ супоришро мефиристад
        $submitResp = $this->actingAs($this->student)->post("/student/assignments/{$assignment->id}/submit", [
            'answer_text' => 'Ман ҳама чизро мутобиқи дастур насб кардам.',
        ]);
        $submitResp->assertStatus(302);

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)->where('user_id', $this->student->id)->first();
        $this->assertNotNull($submission);
        $this->assertEquals('submitted', $submission->status);

        // Муаллиф корро месанҷад ва баҳо медиҳад
        $gradeResp = $this->actingAs($this->author)->patch("/author/submissions/{$submission->id}/grade", [
            'score' => 95,
            'feedback' => 'Офарин, кори олӣ!',
        ]);
        $gradeResp->assertStatus(302);

        $submission->refresh();
        $this->assertEquals(95, $submission->score);
        $this->assertEquals('approved', $submission->status);
    }

    /**
     * 6. Анҷоми тамоми дарсҳо ва тавлиди худкори сертификат.
     */
    public function test_course_completion_generates_certificate(): void
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        // Анҷом додани дарси 1
        $this->actingAs($this->student)->post("/student/lessons/{$this->lesson1->id}/complete");

        // Анҷом додани дарси 2
        $this->actingAs($this->student)->post("/student/lessons/{$this->lesson2->id}/complete");

        $enrollment->refresh();
        $this->assertEquals('completed', $enrollment->status);

        // Сертификат бояд тавлид шуда бошад
        $certificate = Certificate::where('enrollment_id', $enrollment->id)->first();
        $this->assertNotNull($certificate);
        $this->assertStringStartsWith('BIYO-', $certificate->certificate_code);
    }

    /**
     * 7. Модератсияи маъмур: тасдиқ ва рад намудани курс.
     */
    public function test_admin_course_moderation(): void
    {
        $pendingCourse = Course::create([
            'title' => 'Курси нав барои тасдиқ',
            'description' => 'Тавсифи курс...',
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'status' => Course::STATUS_PENDING,
        ]);

        // Маъмур тасдиқ мекунад
        $approveResp = $this->actingAs($this->admin)->patch("/admin/courses/{$pendingCourse->id}/approve");
        $approveResp->assertStatus(302);
        $this->assertEquals(Course::STATUS_PUBLISHED, $pendingCourse->fresh()->status);

        // Маъмур рад мекунад
        $rejectResp = $this->actingAs($this->admin)->patch("/admin/courses/{$pendingCourse->id}/reject");
        $rejectResp->assertStatus(302);
        $this->assertEquals(Course::STATUS_REJECTED, $pendingCourse->fresh()->status);
    }

    /**
     * 8. Идоракунии категория аз тарафи маъмур.
     */
    public function test_admin_category_management(): void
    {
        $resp = $this->actingAs($this->admin)->post('/admin/categories', [
            'title' => 'Дизайни графикӣ',
            'description' => 'Омӯзиши Photoshop ва Figma',
        ]);
        $resp->assertStatus(302);

        $this->assertDatabaseHas('categories', [
            'title' => 'Дизайни графикӣ',
        ]);
    }

    /**
     * 9. Тағйири нақши корбар аз ҷониби маъмур.
     */
    public function test_admin_can_update_user_role(): void
    {
        $resp = $this->actingAs($this->admin)->put("/admin/users/{$this->student->id}", [
            'name' => 'Фарҳод Раҳимов',
            'role' => 'author',
        ]);
        $resp->assertStatus(302);

        $this->assertEquals('author', $this->student->fresh()->role);
    }

    /**
     * 10. Боргузории якчанд намуди маводи таълимӣ (PDF, Word, Video, Image) ба дарс.
     */
    public function test_author_can_upload_diverse_materials_to_lesson(): void
    {
        Storage::fake('public');

        $pdfFile = UploadedFile::fake()->create('manual.pdf', 500, 'application/pdf');
        $docFile = UploadedFile::fake()->create('task.docx', 300, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $videoFile = UploadedFile::fake()->create('lecture.mp4', 5000, 'video/mp4');
        $imgFile = UploadedFile::fake()->create('diagram.png', 200, 'image/png');

        $resp = $this->actingAs($this->author)->post("/author/modules/{$this->module->id}/lessons", [
            'title' => 'Дарси амалӣ бо маводҳо',
            'content_type' => 'text',
            'content_text' => 'Матни дарс бо файлҳои гуногун...',
            'order_index' => 3,
            'materials' => [$pdfFile, $docFile, $videoFile, $imgFile],
            'material_titles' => ['Дастури PDF', 'Супориши Word', 'Видеолексия', 'Диаграммаи меъморӣ'],
        ]);

        $resp->assertStatus(302);

        $lesson = Lesson::where('title', 'Дарси амалӣ бо маводҳо')->first();
        $this->assertNotNull($lesson);
        $this->assertCount(4, $lesson->materials);

        $this->assertDatabaseHas('lesson_materials', [
            'lesson_id' => $lesson->id,
            'title' => 'Дастури PDF',
            'file_type' => 'pdf',
        ]);

        $this->assertDatabaseHas('lesson_materials', [
            'lesson_id' => $lesson->id,
            'title' => 'Супориши Word',
            'file_type' => 'document',
        ]);

        $this->assertDatabaseHas('lesson_materials', [
            'lesson_id' => $lesson->id,
            'title' => 'Видеолексия',
            'file_type' => 'video',
        ]);

        $this->assertDatabaseHas('lesson_materials', [
            'lesson_id' => $lesson->id,
            'title' => 'Диаграммаи меъморӣ',
            'file_type' => 'image',
        ]);
    }

    /**
     * 11. Нест кардани маводи мушаххаси таълимӣ аз ҷониби муаллиф.
     */
    public function test_author_can_delete_material(): void
    {
        Storage::fake('public');
        $material = LessonMaterial::create([
            'lesson_id' => $this->lesson1->id,
            'title' => 'Файли санҷишӣ барои нест кардан',
            'file_path' => 'materials/test.pdf',
            'file_type' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1024,
            'order_index' => 1,
        ]);

        $resp = $this->actingAs($this->author)->delete("/author/materials/{$material->id}");
        $resp->assertStatus(302);

        $this->assertDatabaseMissing('lesson_materials', [
            'id' => $material->id,
        ]);
    }

    /**
     * 12. Санҷиши намуди файл: рад кардани файлҳои номувофиқ.
     */
    public function test_material_upload_rejects_disallowed_mime_types(): void
    {
        Storage::fake('public');
        $exeFile = UploadedFile::fake()->create('virus.exe', 100, 'application/x-msdownload');

        $resp = $this->actingAs($this->author)->post("/author/modules/{$this->module->id}/lessons", [
            'title' => 'Дарс бо файли манъшуда',
            'content_type' => 'text',
            'content_text' => 'Санҷиш...',
            'order_index' => 4,
            'materials' => [$exeFile],
        ]);

        $resp->assertSessionHasErrors(['materials.0']);
    }

    /**
     * 13. Донишҷӯ метавонад маводҳои дарсро бубинад, вақте ки ба курс номнавис аст.
     */
    public function test_student_can_view_materials_when_enrolled(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        LessonMaterial::create([
            'lesson_id' => $this->lesson1->id,
            'title' => 'Маводи махсуси дарс',
            'file_path' => 'materials/sample.pdf',
            'file_type' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 2048,
            'order_index' => 1,
        ]);

        $resp = $this->actingAs($this->student)->get("/student/lessons/{$this->lesson1->id}");
        $resp->assertStatus(200);
        $resp->assertSee('Маводи махсуси дарс');
        $resp->assertSee('Маводҳои таълимии дарс');
    }

    /**
     * 14. Ҳимояи дарсҳои маҳкам: донишҷӯ наметавонад ба маводҳои дарси дуюм бе анҷоми дарси якум дастрасӣ ёбад.
     */
    public function test_student_blocked_from_materials_when_prerequisites_not_completed(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        LessonMaterial::create([
            'lesson_id' => $this->lesson2->id,
            'title' => 'Маводи махфии дарси 2',
            'file_path' => 'materials/secret.pdf',
            'file_type' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 2048,
            'order_index' => 1,
        ]);

        // Кӯшиши дастрасӣ ба дарси 2 бе анҷоми дарси 1 бояд 403 диҳад
        $resp = $this->actingAs($this->student)->get("/student/lessons/{$this->lesson2->id}");
        $resp->assertStatus(403);
    }
}
