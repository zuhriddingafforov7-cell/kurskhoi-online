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
use App\Models\Module;
use App\Models\User;
use App\Support\ModelMeta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MasterSystemVerificationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $author;
    private User $otherAuthor;
    private User $student;
    private User $otherStudent;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        ModelMeta::flush();

        $this->admin = User::factory()->create([
            'name'  => 'Маъмур',
            'email' => 'admin@biyomuz.tj',
            'role'  => 'admin',
        ]);
        $this->author = User::factory()->create([
            'name'  => 'Аҳмад Муаллиф',
            'email' => 'author1@biyomuz.tj',
            'role'  => 'author',
        ]);
        $this->otherAuthor = User::factory()->create([
            'name'  => 'Саид Муаллиф',
            'email' => 'author2@biyomuz.tj',
            'role'  => 'author',
        ]);
        $this->student = User::factory()->create([
            'name'  => 'Донишҷӯ Асосӣ',
            'email' => 'student1@biyomuz.tj',
            'role'  => 'student',
        ]);
        $this->otherStudent = User::factory()->create([
            'name'  => 'Донишҷӯ Бегона',
            'email' => 'student2@biyomuz.tj',
            'role'  => 'student',
        ]);

        $this->category = Category::create([
            'title'       => 'Барномасозӣ',
            'slug'        => 'programming',
            'description' => 'Курсҳои барномасозӣ',
        ]);
    }

    protected function tearDown(): void
    {
        ModelMeta::flush();
        parent::tearDown();
    }

    private function fakePng(string $name = 'cover.png'): UploadedFile
    {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');

        return UploadedFile::fake()->createWithContent($name, $png);
    }

    public function test_guest_homepage_catalog_filters_and_protected_routes(): void
    {
        $c1 = Course::create([
            'title'       => 'Асосҳои PHP',
            'description' => 'Курси омӯзиши PHP барои навомӯзон',
            'category_id' => $this->category->id,
            'author_id'   => $this->author->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);
        ModelMeta::set('course', $c1->id, 'level', 'beginner');

        $c2 = Course::create([
            'title'       => 'Амнияти пешрафта дар Laravel',
            'description' => 'Курси касбии меъморӣ ва амният',
            'category_id' => $this->category->id,
            'author_id'   => $this->otherAuthor->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);
        ModelMeta::set('course', $c2->id, 'level', 'advanced');

        $this->get('/')->assertOk()->assertSee('Biyomuz Academy')->assertSee($c1->title);

        $this->get(route('courses.index', ['level' => 'advanced', 'author' => $this->otherAuthor->id]))
            ->assertOk()
            ->assertSee($c2->title)
            ->assertDontSee($c1->title);

        $this->get(route('courses.index', ['search' => 'PHP', 'sort' => 'title']))
            ->assertOk()
            ->assertSee($c1->title)
            ->assertDontSee($c2->title);

        // Меҳмон ба панелҳои муҳофизатшуда ворид шуда наметавонад
        $this->get('/student/dashboard')->assertRedirect('/login');
        $this->get('/author/dashboard')->assertRedirect('/login');
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/profile')->assertRedirect('/login');
    }

    public function test_locale_switcher_changes_rendered_ui_language(): void
    {
        $this->get(route('locale.switch', 'ru'))->assertRedirect();
        $this->assertSame('ru', session('locale'));

        $this->get('/')
            ->assertOk()
            ->assertSee('Каталог курсов')
            ->assertSee('Войти');

        $this->get(route('locale.switch', 'tg'))->assertRedirect();
        $this->assertSame('tg', session('locale'));

        $this->get('/')
            ->assertOk()
            ->assertSee('Каталоги курсҳо');
    }

    public function test_authenticated_user_can_update_profile_and_password_without_role_escalation(): void
    {
        $this->actingAs($this->student)
            ->patch(route('profile.update'), [
                'name'  => 'Донишҷӯ Нав',
                'email' => 'student.updated@biyomuz.tj',
                'role'  => 'admin', // бояд нодида гирифта шавад
            ])
            ->assertRedirect();

        $this->student->refresh();
        $this->assertSame('Донишҷӯ Нав', $this->student->name);
        $this->assertSame('student.updated@biyomuz.tj', $this->student->email);
        $this->assertSame('student', $this->student->role);

        $this->actingAs($this->student)
            ->put(route('profile.password'), [
                'current_password'      => 'password',
                'password'              => 'newsecret123',
                'password_confirmation' => 'newsecret123',
            ])
            ->assertRedirect();

        $this->assertTrue(Hash::check('newsecret123', $this->student->fresh()->password));
    }

    public function test_sequential_lesson_enforcement_blocks_get_complete_post_and_locked_assignment(): void
    {
        $course = Course::create([
            'title'       => 'Курси сохторӣ',
            'description' => 'Пайдарпайӣ',
            'category_id' => $this->category->id,
            'author_id'   => $this->author->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);
        $mod = Module::create(['course_id' => $course->id, 'title' => 'Модули 1', 'order_index' => 1]);
        $lesson1 = Lesson::create(['module_id' => $mod->id, 'title' => 'Дарси 1', 'content_type' => 'text', 'order_index' => 1]);
        $lesson2 = Lesson::create(['module_id' => $mod->id, 'title' => 'Дарси 2', 'content_type' => 'text', 'order_index' => 2]);
        $assignment2 = Assignment::create([
            'lesson_id'   => $lesson2->id,
            'title'       => 'Супориши дарси 2',
            'description' => 'Иҷро кунед',
            'max_score'   => 100,
        ]);

        // Номнависии такрорӣ бехатар (idempotent)
        $this->actingAs($this->student)->post(route('student.courses.enroll', $course))->assertRedirect();
        $this->actingAs($this->student)->post(route('student.courses.enroll', $course))->assertRedirect();
        $this->assertSame(1, Enrollment::where('user_id', $this->student->id)->where('course_id', $course->id)->count());

        // Дарси 2 то анҷоми дарси 1 ҳам дар GET, ҳам дар POST /complete ва ҳам дар супориш баста аст
        $this->actingAs($this->student)->get(route('student.lessons.show', $lesson2))->assertForbidden();
        $this->actingAs($this->student)->post(route('student.lessons.complete', $lesson2))->assertForbidden();
        $this->actingAs($this->student)->get(route('student.assignments.show', $assignment2))->assertForbidden();
        $this->actingAs($this->student)->post(route('student.assignments.submit', $assignment2), [
            'answer_text' => 'Кӯшиши убур',
        ])->assertForbidden();

        // Дарси 1-ро анҷом медиҳем -> Дарси 2 ва супориши он кушода мешаванд
        $this->actingAs($this->student)->post(route('student.lessons.complete', $lesson1))->assertRedirect();
        $this->actingAs($this->student)->get(route('student.lessons.show', $lesson2))->assertOk();
        $this->actingAs($this->student)->get(route('student.assignments.show', $assignment2))->assertOk();
    }

    public function test_private_assignment_upload_validation_idor_and_grading_resubmit_certificate_flow(): void
    {
        Storage::fake('local');

        $course = Course::create([
            'title'       => 'Курси сертификатдор',
            'description' => 'Санҷиши пурраи сертификат',
            'category_id' => $this->category->id,
            'author_id'   => $this->author->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);
        $mod = Module::create(['course_id' => $course->id, 'title' => 'Модул', 'order_index' => 1]);
        $lesson = Lesson::create(['module_id' => $mod->id, 'title' => 'Дарс', 'content_type' => 'text', 'order_index' => 1]);
        $assignment = Assignment::create([
            'lesson_id'   => $lesson->id,
            'title'       => 'Кори амалӣ',
            'description' => 'PDF ё ZIP фиристед',
            'max_score'   => 100,
        ]);

        Enrollment::create([
            'user_id'     => $this->student->id,
            'course_id'   => $course->id,
            'enrolled_at' => now(),
            'status'      => 'active',
        ]);
        $this->actingAs($this->student)->post(route('student.lessons.complete', $lesson))->assertRedirect();

        // Файли беш аз 5 МБ ё MIME-и номувофиқ рад мешавад
        $this->actingAs($this->student)
            ->post(route('student.assignments.submit', $assignment), [
                'file' => UploadedFile::fake()->create('malware.exe', 100),
            ])
            ->assertSessionHasErrors('file');

        $this->actingAs($this->student)
            ->post(route('student.assignments.submit', $assignment), [
                'file' => UploadedFile::fake()->create('too-big.pdf', 6000, 'application/pdf'),
            ])
            ->assertSessionHasErrors('file');

        // Супориши дурусти PDF ба диски хусусии local захира мешавад
        $pdf = UploadedFile::fake()->create('solution.pdf', 200, 'application/pdf');
        $this->actingAs($this->student)
            ->post(route('student.assignments.submit', $assignment), [
                'answer_text' => 'Ҳалли аввал',
                'file'        => $pdf,
            ])
            ->assertRedirect();

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $this->student->id)
            ->firstOrFail();

        Storage::disk('local')->assertExists($submission->file_path);

        // Муҳофизати IDOR: донишҷӯи бегона ва муаллифи бегона наметавонанд боргирӣ кунанд
        $this->actingAs($this->otherStudent)->get(route('submissions.download', $submission))->assertForbidden();
        $this->actingAs($this->otherAuthor)->get(route('submissions.download', $submission))->assertForbidden();

        // Соҳиб ва муаллифи курс метавонанд боргирӣ кунанд
        $this->actingAs($this->student)->get(route('submissions.download', $submission))->assertOk();
        $this->actingAs($this->author)->get(route('submissions.download', $submission))->assertOk();

        // Муаллифи бегона баҳо дода наметавонад
        $this->actingAs($this->otherAuthor)
            ->patch(route('author.submissions.grade', $submission), ['score' => 50])
            ->assertForbidden();

        // Муаллиф корро рад мекунад -> сертификат дода намешавад ва таърих сабт мешавад
        $this->actingAs($this->author)
            ->patch(route('author.submissions.grade', $submission), [
                'score'    => 40,
                'status'   => 'rejected',
                'feedback' => 'Бояд ислоҳ карда шавад',
            ])
            ->assertRedirect();

        $this->assertSame('rejected', $submission->fresh()->status);
        $this->assertSame(0, Certificate::count());
        $this->assertCount(1, $submission->fresh()->grading_history);

        // Донишҷӯ рад шуданро мебинад ва дубора мефиристад
        $this->actingAs($this->student)
            ->get(route('student.assignments.show', $assignment))
            ->assertOk()
            ->assertSee('Бояд ислоҳ карда шавад');

        $this->actingAs($this->student)
            ->post(route('student.assignments.submit', $assignment), [
                'answer_text' => 'Ҳалли ислоҳшуда',
            ])
            ->assertRedirect();

        $this->assertSame('submitted', $submission->fresh()->status);

        // Муаллиф тасдиқ мекунад -> курс хатм шуда сертификат сохта мешавад
        $this->actingAs($this->author)
            ->patch(route('author.submissions.grade', $submission), [
                'score'    => 95,
                'status'   => 'approved',
                'feedback' => 'Аъло!',
            ])
            ->assertRedirect();

        $this->assertSame('approved', $submission->fresh()->status);
        $this->assertCount(2, $submission->fresh()->grading_history);

        $enrollment = Enrollment::where('user_id', $this->student->id)->where('course_id', $course->id)->firstOrFail();
        $this->assertSame('completed', $enrollment->status);

        $cert = Certificate::where('enrollment_id', $enrollment->id)->first();
        $this->assertNotNull($cert);

        // Боргирии сертификати PDF аз ҷониби соҳиб (200) ва рад барои донишҷӯи дигар (403)
        $this->actingAs($this->otherStudent)
            ->get(route('student.certificates.download', $cert))
            ->assertForbidden();

        $pdfResponse = $this->actingAs($this->student)
            ->get(route('student.certificates.download', $cert));
        $pdfResponse->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdfResponse->headers->get('Content-Type'));
        $this->assertStringStartsWith('%PDF', $pdfResponse->getContent());
    }

    public function test_author_full_course_lesson_material_link_workflow_and_protected_material_access(): void
    {
        Storage::fake('local');

        // Муаллиф курси сиёҳнавис бо сатҳ ва муқова месозад
        $this->actingAs($this->author)
            ->post(route('author.courses.store'), [
                'title'       => 'Курси мукаммали муаллиф',
                'description' => 'Тавсифи пурраи курс',
                'category_id' => $this->category->id,
                'level'       => 'intermediate',
                'status'      => 'draft',
                'cover_image' => $this->fakePng('course-cover.png'),
            ])
            ->assertRedirect();

        $course = Course::where('title', 'Курси мукаммали муаллиф')->firstOrFail();
        $this->assertSame(Course::STATUS_DRAFT, $course->status);
        $this->assertSame('intermediate', $course->level);
        $this->assertNotNull($course->cover_image);
        Storage::disk('local')->assertExists($course->cover_image);

        // Муқоваи сиёҳнавис ба меҳмон пинҳон (404), аммо ба муаллиф дастрас (200)
        $this->app->make('auth')->forgetGuards();
        $this->get(route('courses.cover', $course))->assertNotFound();
        $this->actingAs($this->author)->get(route('courses.cover', $course))->assertOk();

        // Муаллиф барои модератсия мефиристад ва Маъмур тасдиқ мекунад
        $this->actingAs($this->author)->patch(route('author.courses.submit', $course))->assertRedirect();
        $this->assertSame(Course::STATUS_PENDING, $course->fresh()->status);

        // Маъмур аввал бо сабаб рад мекунад, баъд тасдиқ мекунад
        $this->actingAs($this->admin)
            ->patch(route('admin.courses.reject', $course), ['rejection_reason' => 'Модулҳоро илова кунед'])
            ->assertRedirect();
        $this->assertSame(Course::STATUS_REJECTED, $course->fresh()->status);
        $this->assertSame('Модулҳоро илова кунед', $course->fresh()->rejection_reason);

        // Модул бо тавсиф ва дарс бо ҳама намуди файлҳо + пайвандҳои беруна + давомнокӣ
        $this->actingAs($this->author)
            ->post(route('author.courses.modules.store', $course), [
                'title'       => 'Модули аввал',
                'description' => 'Тавсифи модули аввал',
                'order_index' => 1,
            ])
            ->assertRedirect();

        $module = Module::where('course_id', $course->id)->firstOrFail();
        $this->assertSame('Тавсифи модули аввал', $module->description);

        $diverseFiles = [
            UploadedFile::fake()->create('video1.mp4', 100, 'video/mp4'),
            UploadedFile::fake()->create('video2.webm', 100, 'video/webm'),
            UploadedFile::fake()->create('guide.pdf', 150, 'application/pdf'),
            UploadedFile::fake()->create('legacy.doc', 100, 'application/msword'),
            UploadedFile::fake()->create('spec.docx', 100, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
            UploadedFile::fake()->create('slides.ppt', 100, 'application/vnd.ms-powerpoint'),
            UploadedFile::fake()->create('slides2.pptx', 100, 'application/vnd.openxmlformats-officedocument.presentationml.presentation'),
            UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg'),
            UploadedFile::fake()->create('diagram.png', 100, 'image/png'),
            UploadedFile::fake()->create('banner.webp', 100, 'image/webp'),
        ];

        $this->actingAs($this->author)
            ->post(route('author.modules.lessons.store', $module), [
                'title'                 => 'Дарси аввал',
                'content_type'          => 'file',
                'content_text'          => 'Матни дарс',
                'order_index'           => 1,
                'duration_minutes'      => 25,
                'status'                => 'published',
                'materials'             => $diverseFiles,
                'material_titles'       => ['MP4', 'WebM', 'Дастури PDF', 'DOC', 'DOCX', 'PPT', 'PPTX', 'JPG', 'PNG', 'WEBP'],
                'material_descriptions' => ['Шарҳи видео', '', 'Шарҳи муфассал'],
                'link_urls'             => [
                    'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'https://vimeo.com/123456789',
                    'https://laravel.com/docs',
                ],
                'link_titles'           => ['Видеодарси YouTube', 'Видеодарси Vimeo', 'Ҳуҷҷатгузории Laravel'],
                'link_types'            => ['video', 'video', 'link'],
                'link_descriptions'     => ['Тавзеҳи YouTube', 'Тавзеҳи Vimeo', 'Мақолаи расмӣ'],
            ])
            ->assertRedirect();

        $lesson = Lesson::where('module_id', $module->id)->firstOrFail();
        $this->assertSame(25, $lesson->duration_minutes);
        $this->assertSame('published', $lesson->status);
        $this->assertCount(13, $lesson->materials);

        $uploadedMaterial = $lesson->materials()->where('file_type', 'pdf')->firstOrFail();
        $ytMaterial = $lesson->materials()->where('title', 'Видеодарси YouTube')->firstOrFail();
        $vimeoMaterial = $lesson->materials()->where('title', 'Видеодарси Vimeo')->firstOrFail();
        $webLinkMaterial = $lesson->materials()->where('title', 'Ҳуҷҷатгузории Laravel')->firstOrFail();
        $this->assertSame('Шарҳи муфассал', $uploadedMaterial->description);
        $this->assertTrue($ytMaterial->is_link);
        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $ytMaterial->embed_url);
        $this->assertSame('https://player.vimeo.com/video/123456789', $vimeoMaterial->embed_url);
        $this->assertSame('link', $webLinkMaterial->file_type);

        // Нест кардани яке аз маводҳо аз тарафи муаллиф
        $removable = $lesson->materials()->where('title', 'DOC')->firstOrFail();
        $this->actingAs($this->author)->delete(route('author.materials.destroy', $removable))->assertRedirect();
        $this->assertDatabaseMissing('lesson_materials', ['id' => $removable->id]);

        // Таҳрири маводи мавҷуда ва пешнамоиши дарс аз тарафи муаллиф
        $this->actingAs($this->author)
            ->put(route('author.materials.update', $uploadedMaterial), [
                'title'       => 'Дастури навшуда',
                'description' => 'Тавсифи навшуда',
                'order_index' => 1,
            ])
            ->assertRedirect();
        $this->assertSame('Дастури навшуда', $uploadedMaterial->fresh()->title);

        $this->actingAs($this->author)->get(route('author.lessons.preview', $lesson))->assertOk();

        // Дастрасии муҳофизатшуда ба мавод: бе номнависӣ 403, ба муаллиф 200
        $this->actingAs($this->student)->get(route('materials.download', $uploadedMaterial))->assertForbidden();
        $this->actingAs($this->author)->get(route('materials.download', $uploadedMaterial))->assertOk();
        $this->actingAs($this->author)->get(route('materials.stream', $uploadedMaterial))->assertOk();

        // Маъмур курсро тасдиқ мекунад ва донишҷӯ номнавис шуда маводро мегирад
        $this->actingAs($this->admin)->patch(route('admin.courses.approve', $course))->assertRedirect();
        $this->get(route('courses.cover', $course))->assertOk();

        $this->actingAs($this->student)->post(route('student.courses.enroll', $course))->assertRedirect();
        $this->actingAs($this->student)->get(route('materials.download', $uploadedMaterial))->assertOk();

        // Курсе, ки донишҷӯ дорад, нест карда намешавад
        $this->actingAs($this->author)->delete(route('author.courses.destroy', $course))->assertSessionHas('error');
        $this->assertDatabaseHas('courses', ['id' => $course->id]);
    }

    public function test_dual_url_localhost_http_and_public_https_asset_and_link_generation(): void
    {
        $this->withVite();

        Course::create([
            'title'       => 'Курси санҷиши ду суроға',
            'description' => 'Тавсиф',
            'category_id' => $this->category->id,
            'author_id'   => $this->author->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);

        // 1. Localhost HTTP бояд пайвандҳо ва ассетҳоро бо http://127.0.0.1:8000 созад
        $localHome = $this->get('http://127.0.0.1:8000/');
        $localHome->assertOk();
        $localHome->assertSee('http://127.0.0.1:8000/courses', false);
        $localHome->assertSee('http://127.0.0.1:8000/build/assets/', false);
        $localHome->assertDontSee('https://127.0.0.1:8000', false);

        $localCatalog = $this->get('http://127.0.0.1:8000/courses');
        $localCatalog->assertOk();
        $localCatalog->assertDontSee('https://127.0.0.1:8000', false);

        // 2. Public HTTPS тавассути Tailscale Funnel (X-Forwarded-Proto: https) бояд https://biyomuzacademy.tail10526e.ts.net созад
        $publicHome = $this->withHeaders([
            'X-Forwarded-Proto' => 'https',
        ])->get('https://biyomuzacademy.tail10526e.ts.net/');
        $publicHome->assertOk();
        $publicHome->assertSee('https://biyomuzacademy.tail10526e.ts.net/courses', false);
        $publicHome->assertSee('https://biyomuzacademy.tail10526e.ts.net/build/assets/', false);
        $publicHome->assertDontSee('http://127.0.0.1:8000', false);
    }

    public function test_admin_csv_export_and_category_deletion_protection(): void
    {
        $course = Course::create([
            'title'       => 'Курси содиротӣ',
            'description' => 'Тавсиф',
            'category_id' => $this->category->id,
            'author_id'   => $this->author->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);

        // Категорияе, ки курс дорад, нест намешавад
        $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $this->category))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $this->category->id]);

        // Содироти CSV хатмкунандагон (FR-12)
        $enrollment = Enrollment::create([
            'user_id'     => $this->student->id,
            'course_id'   => $course->id,
            'enrolled_at' => now(),
            'status'      => 'completed',
        ]);
        Certificate::create([
            'enrollment_id'    => $enrollment->id,
            'certificate_code' => 'BIYO-2026-99999',
            'pdf_path'         => 'certificates/BIYO-2026-99999.pdf',
            'issue_date'       => now(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.certificates.export'));
        $response->assertOk();
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('Content-Type'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('BIYO-2026-99999', $content);
        $this->assertStringContainsString($this->student->name, $content);
    }
}
