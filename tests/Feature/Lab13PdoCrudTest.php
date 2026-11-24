<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Module;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use PDO;
use PDOException;
use Src\Core\Database;
use Src\Repositories\CourseRepository;
use Src\Repositories\EnrollmentRepository;
use Tests\TestCase;

class Lab13PdoCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $authorA;
    private User $authorB;
    private User $student;
    private Category $catProg;
    private Category $catDesign;
    private Category $catEmpty;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name'  => 'Маъмури Асосӣ',
            'email' => 'admin13@biyomuz.tj',
            'role'  => 'admin',
        ]);
        $this->authorA = User::factory()->create([
            'name'  => 'Аҳмад Муаллиф',
            'email' => 'authorA@biyomuz.tj',
            'role'  => 'author',
        ]);
        $this->authorB = User::factory()->create([
            'name'  => 'Шаҳром Муаллиф',
            'email' => 'authorB@biyomuz.tj',
            'role'  => 'author',
        ]);
        $this->student = User::factory()->create([
            'name'  => 'Донишҷӯи Санҷишӣ',
            'email' => 'student13@biyomuz.tj',
            'role'  => 'student',
        ]);

        $this->catProg = Category::create([
            'title'       => 'Барномасозӣ',
            'slug'        => 'programming',
            'description' => 'Курсҳои барномасозӣ',
        ]);
        $this->catDesign = Category::create([
            'title'       => 'Веб-дизайн',
            'slug'        => 'web-design',
            'description' => 'Курсҳои дизайн',
        ]);
        $this->catEmpty = Category::create([
            'title'       => 'Амнияти иттилоотӣ',
            'slug'        => 'cybersecurity',
            'description' => 'Категорияи холӣ барои санҷиши LEFT JOIN',
        ]);
    }

    public function test_pdo_connection_and_required_options_are_configured(): void
    {
        $options = Database::options();
        $this->assertSame(PDO::ERRMODE_EXCEPTION, $options[PDO::ATTR_ERRMODE]);
        $this->assertSame(PDO::FETCH_ASSOC, $options[PDO::ATTR_DEFAULT_FETCH_MODE]);
        $this->assertFalse($options[PDO::ATTR_EMULATE_PREPARES]);

        $pdo = Database::connection();
        $this->assertSame(PDO::ERRMODE_EXCEPTION, $pdo->getAttribute(PDO::ATTR_ERRMODE));
        $this->assertSame(PDO::FETCH_ASSOC, $pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE));
    }

    public function test_catalog_paginates_10_per_page_and_clamps_edge_page_numbers(): void
    {
        for ($i = 1; $i <= 24; $i++) {
            Course::create([
                'title'       => sprintf('Курси санҷишии %02d', $i),
                'description' => 'Тавсифи пурраи курси санҷишии пагинация рақами ' . $i,
                'category_id' => $this->catProg->id,
                'author_id'   => $this->authorA->id,
                'status'      => Course::STATUS_PUBLISHED,
            ]);
        }

        // Саҳифаи 1: маҳз 10 корти курс
        $p1 = $this->get('/courses');
        $p1->assertOk();
        $p1->assertSee('Натиҷа: <strong class="text-[#1E293B]">24</strong> курс', false);

        $repo = app(CourseRepository::class);
        $page1 = $repo->paginate(['status' => 'published'], 1, 10);
        $page2 = $repo->paginate(['status' => 'published'], 2, 10);
        $page3 = $repo->paginate(['status' => 'published'], 3, 10);
        $pageNeg = $repo->paginate(['status' => 'published'], -1, 10);
        $pageOver = $repo->paginate(['status' => 'published'], 999, 10);

        $this->assertCount(10, $page1['items']);
        $this->assertCount(10, $page2['items']);
        $this->assertCount(4, $page3['items']);
        $this->assertSame(1, $pageNeg['page']);
        $this->assertSame(3, $pageOver['page']);
        $this->assertNotSame($page1['items'][0]['id'], $page2['items'][0]['id']);

        // Санҷиши HTTP бо page=-1, page=abc, page=999
        $this->get('/courses?page=-1')->assertOk();
        $this->get('/courses?page=abc')->assertOk();
        $this->get('/courses?page=999')->assertOk();
    }

    public function test_search_like_escaping_sqli_protection_and_filter_preservation(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Course::create([
                'title'       => "Курси махсуси Барномасозӣ {$i}",
                'description' => 'Тавсифи мукаммали дарсҳои PHP ва SQL',
                'category_id' => $this->catProg->id,
                'author_id'   => $this->authorA->id,
                'status'      => Course::STATUS_PUBLISHED,
            ]);
        }

        Course::create([
            'title'       => 'Омӯзиши 100% амалии SQL_Injection',
            'description' => 'Махсус барои санҷиши % ва _ дар LIKE',
            'category_id' => $this->catDesign->id,
            'author_id'   => $this->authorB->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);

        // 1. SQL Injection бояд 0 сабт диҳад ва хатои SQL наорад
        $sqli = $this->actingAs($this->admin)->get('/manage/courses?q=' . urlencode("' OR 1=1 --"));
        $sqli->assertOk();
        $sqli->assertSee('Ёфт шуд: <strong class="text-[#1E293B]">0</strong> курс', false);

        // 2. `%` ва `_` набояд ҳамаи 13 курсро баргардонанд
        $percentResp = $this->actingAs($this->admin)->get('/manage/courses?q=' . urlencode('%'));
        $percentResp->assertOk();
        $percentResp->assertSee('Ёфт шуд: <strong class="text-[#1E293B]">1</strong> курс', false);
        $percentResp->assertSee('Омӯзиши 100% амалии SQL_Injection');

        $underscoreResp = $this->actingAs($this->admin)->get('/manage/courses?q=' . urlencode('_'));
        $underscoreResp->assertOk();
        $underscoreResp->assertSee('Ёфт шуд: <strong class="text-[#1E293B]">1</strong> курс', false);

        // 3. Ду филтр якҷоя + нигоҳдории онҳо дар линкҳои саҳифабандӣ
        $filteredPage1 = $this->actingAs($this->admin)->get("/manage/courses?category_id={$this->catProg->id}&status=published");
        $filteredPage1->assertOk();
        $filteredPage1->assertSee('Ёфт шуд: <strong class="text-[#1E293B]">12</strong> курс', false);
        $filteredPage1->assertSee("category_id={$this->catProg->id}", false);
        $filteredPage1->assertSee('status=published', false);
        $filteredPage1->assertSee('page=2', false);

        $filteredPage2 = $this->actingAs($this->admin)->get("/manage/courses?category_id={$this->catProg->id}&status=published&page=2");
        $filteredPage2->assertOk();
        $filteredPage2->assertSee('Саҳифаи <strong class="text-[#1E293B]">2</strong> аз <strong class="text-[#1E293B]">2</strong>', false);
    }

    public function test_join_displays_category_and_author_names_and_rbac_is_enforced(): void
    {
        $courseA = Course::create([
            'title'       => 'Курси JOIN Санҷишӣ',
            'description' => 'Тавсифи пурраи курси JOIN барои санҷиш',
            'category_id' => $this->catDesign->id,
            'author_id'   => $this->authorA->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);

        // Номҳои категория ва муаллиф дар рӯйхат ва саҳифаи ҷудогона (JOIN)
        $this->actingAs($this->admin)
            ->get('/manage/courses')
            ->assertOk()
            ->assertSee('Веб-дизайн')
            ->assertSee('Аҳмад Муаллиф');

        $this->actingAs($this->admin)
            ->get("/manage/courses/{$courseA->id}")
            ->assertOk()
            ->assertSee('Веб-дизайн')
            ->assertSee('Аҳмад Муаллиф');

        // Меҳмон -> redirect ба login; донишҷӯ -> 403
        $this->app->make('auth')->forgetGuards();
        $this->get('/manage/courses')->assertRedirect('/login');
        $this->actingAs($this->student)->get('/manage/courses')->assertForbidden();
        $this->actingAs($this->student)->post('/manage/courses', [])->assertForbidden();

        // Муаллифи дигар (authorB) наметавонад курси authorA-ро бинад ё тағйир диҳад
        $this->actingAs($this->authorB)->get("/manage/courses/{$courseA->id}")->assertForbidden();
        $this->actingAs($this->authorB)->post("/manage/courses/{$courseA->id}/delete")->assertForbidden();

        // authorB наметавонад тавассути POST author_id-ро ба authorA тағйир диҳад
        $createResp = $this->actingAs($this->authorB)->post('/manage/courses', [
            'title'       => 'Курси муаллифи Б бо санҷиши амният',
            'description' => 'Санҷиши назорати author_id дар сервер',
            'category_id' => $this->catProg->id,
            'status'      => 'published', // муаллиф иҷозати мустақими published надорад -> 422
            'author_id'   => $this->authorA->id,
        ]);
        $createResp->assertStatus(422);

        $validResp = $this->actingAs($this->authorB)->post('/manage/courses', [
            'title'       => 'Курси дурусти муаллифи Б',
            'description' => 'Санҷиши он ки author_id танҳо корбари ҷорӣ мешавад',
            'category_id' => $this->catProg->id,
            'status'      => 'pending',
            'author_id'   => $this->authorA->id, // бояд нодида гирифта шавад
        ]);
        $validResp->assertStatus(302);
        $this->assertDatabaseHas('courses', [
            'title'     => 'Курси дурусти муаллифи Б',
            'author_id' => $this->authorB->id,
        ]);
    }

    public function test_create_and_update_validation_return_http_422_and_check_unique_title(): void
    {
        $existing = Course::create([
            'title'       => 'Курси ягонаи санҷишӣ',
            'description' => 'Тавсифи пурраи курси мавҷуда дар база',
            'category_id' => $this->catProg->id,
            'author_id'   => $this->admin->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);

        // 1. Маълумоти нодуруст -> HTTP 422 + нигоҳдории матни воридшуда
        $invalidResp = $this->actingAs($this->admin)->post('/manage/courses', [
            'title'       => 'АБ',
            'description' => 'Кӯтоҳ',
            'category_id' => '999999',
            'status'      => 'invalid_status',
        ]);
        $invalidResp->assertStatus(422);
        $invalidResp->assertSee('value="АБ"', false);
        $invalidResp->assertSee('Кӯтоҳ');
        $invalidResp->assertSee('Номи курс бояд ҳадди ақал 3 аломат дошта бошад.');

        // 2. Номи такрорӣ -> HTTP 422 (қоидаи UNIQUE бо муроҷиат ба DB)
        $dupResp = $this->actingAs($this->admin)->post('/manage/courses', [
            'title'       => '  курси ягонаи санҷишӣ  ',
            'description' => 'Тавсифи кофӣ барои санҷиши такрори ном дар база',
            'category_id' => $this->catProg->id,
            'status'      => 'pending',
        ]);
        $dupResp->assertStatus(422);
        $dupResp->assertSee('Курс бо чунин ном аллакай дар пойгоҳи дода мавҷуд аст.');

        // 3. Иловаи дуруст -> HTTP 302 PRG
        $storeResp = $this->actingAs($this->admin)->post('/manage/courses', [
            'title'       => 'Курси нави комилан дуруст',
            'description' => 'Тавсифи муфассали курси нав бо ҳамаи талабот',
            'category_id' => $this->catDesign->id,
            'status'      => 'published',
        ]);
        $storeResp->assertStatus(302);
        $newCourse = Course::where('title', 'Курси нави комилан дуруст')->firstOrFail();
        $storeResp->assertRedirect("/manage/courses/{$newCourse->id}");

        // 4. Таҳрири курс бо нигоҳдории номи худ -> HTTP 302
        $selfUpdate = $this->actingAs($this->admin)->post("/manage/courses/{$newCourse->id}/update", [
            'title'       => 'Курси нави комилан дуруст',
            'description' => 'Тавсифи навсозишудаи курси нав бо дарозии дуруст',
            'category_id' => $this->catProg->id,
            'status'      => 'draft',
        ]);
        $selfUpdate->assertStatus(302);

        // 5. Таҳрир бо номи курси дигар -> HTTP 422
        $conflictUpdate = $this->actingAs($this->admin)->post("/manage/courses/{$newCourse->id}/update", [
            'title'       => $existing->title,
            'description' => 'Кӯшиши гирифтани номи курси дигар',
            'category_id' => $this->catProg->id,
            'status'      => 'draft',
        ]);
        $conflictUpdate->assertStatus(422);
        $conflictUpdate->assertSee('Курс бо чунин ном аллакай дар пойгоҳи дода мавҷуд аст.');
    }

    public function test_post_delete_protects_fk_dependencies_and_deletes_unreferenced_course(): void
    {
        $protectedCourse = Course::create([
            'title'       => 'Курси дорои донишҷӯён',
            'description' => 'Набояд нест карда шавад, зеро номнависӣ дорад',
            'category_id' => $this->catProg->id,
            'author_id'   => $this->admin->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);

        Enrollment::create([
            'user_id'   => $this->student->id,
            'course_id' => $protectedCourse->id,
            'status'    => 'active',
        ]);

        // Несткунӣ бо GET иҷозат дода намешавад (POST-only)
        $this->actingAs($this->admin)->get("/manage/courses/{$protectedCourse->id}/delete")->assertStatus(405);

        // Кӯшиши нест кардани курси дорои донишҷӯ: паёми фаҳмо, бе 500, сабтҳо ҳифз мешаванд
        $blockedResp = $this->actingAs($this->admin)->post("/manage/courses/{$protectedCourse->id}/delete");
        $blockedResp->assertStatus(302);
        $blockedResp->assertSessionHas('error');
        $this->assertDatabaseHas('courses', ['id' => $protectedCourse->id]);
        $this->assertDatabaseHas('enrollments', ['course_id' => $protectedCourse->id]);

        // Курси дорои модул низ бехатар ҳифз мешавад
        $moduleCourse = Course::create([
            'title'       => 'Курси дорои модул',
            'description' => 'Модулҳо набояд нохост бо CASCADE нест шаванд',
            'category_id' => $this->catProg->id,
            'author_id'   => $this->admin->id,
            'status'      => Course::STATUS_DRAFT,
        ]);
        Module::create([
            'course_id'   => $moduleCourse->id,
            'title'       => 'Модули 1',
            'order_index' => 1,
        ]);

        $modBlockedResp = $this->actingAs($this->admin)->post("/manage/courses/{$moduleCourse->id}/delete");
        $modBlockedResp->assertStatus(302);
        $modBlockedResp->assertSessionHas('error');
        $this->assertDatabaseHas('courses', ['id' => $moduleCourse->id]);

        // Курси бе вобастагӣ бо POST бомуваффақият нест мешавад
        $freeCourse = Course::create([
            'title'       => 'Курси бе вобастагӣ барои несткунӣ',
            'description' => 'Ягон донишҷӯ ё модул надорад',
            'category_id' => $this->catDesign->id,
            'author_id'   => $this->admin->id,
            'status'      => Course::STATUS_DRAFT,
        ]);

        $deleteResp = $this->actingAs($this->admin)->post("/manage/courses/{$freeCourse->id}/delete");
        $deleteResp->assertStatus(302);
        $deleteResp->assertRedirect('/manage/courses');
        $deleteResp->assertSessionHas('success');
        $this->assertDatabaseMissing('courses', ['id' => $freeCourse->id]);
    }

    public function test_group_by_report_and_safe_500_error_view(): void
    {
        $c1 = Course::create([
            'title'       => 'Курси 1',
            'description' => 'Тавсифи курси якум',
            'category_id' => $this->catProg->id,
            'author_id'   => $this->authorA->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);
        Course::create([
            'title'       => 'Курси 2',
            'description' => 'Тавсифи курси дуюм',
            'category_id' => $this->catProg->id,
            'author_id'   => $this->authorA->id,
            'status'      => Course::STATUS_PENDING,
        ]);
        Enrollment::create([
            'user_id'   => $this->student->id,
            'course_id' => $c1->id,
            'status'    => 'active',
        ]);

        $report = $this->get('/reports/courses');
        $report->assertOk();
        $report->assertSee('Шумораи курсҳо аз рӯйи категорияҳо');
        $report->assertSee('Ҳисоботи таҳлилӣ');
        $report->assertDontSee('GROUP BY + LEFT JOIN');
        $report->assertDontSee('PDO Repository');
        $report->assertSee('Барномасозӣ');
        $report->assertSee('Амнияти иттилоотӣ');
        $report->assertSee('Ҷамъбасти умумӣ');

        // Санҷиши саҳифаи 500 бидуни фош шудани тафсилоти SQL ҳангоми APP_DEBUG=false
        Config::set('app.debug', false);
        $this->app->bind(CourseRepository::class, function (): CourseRepository {
            throw new PDOException('SQLSTATE[42000]: Syntax error or access violation near SECRET_TABLE');
        });

        $errorResp = $this->get('/courses');
        $errorResp->assertStatus(500);
        $errorResp->assertSee('Хатои дохилии сервер');
        $errorResp->assertDontSee('SQLSTATE');
        $errorResp->assertDontSee('SECRET_TABLE');
    }

    public function test_enrollment_pdo_transaction_commits_two_tables_rolls_back_on_second_op_error_and_prevents_duplicates(): void
    {
        $course = Course::create([
            'title'       => 'Курси транзаксияи PDO',
            'description' => 'Курс бо ду дарс барои санҷиши транзаксияи PDO',
            'category_id' => $this->catProg->id,
            'author_id'   => $this->authorA->id,
            'status'      => Course::STATUS_PUBLISHED,
        ]);

        $module = Module::create([
            'course_id'   => $course->id,
            'title'       => 'Модули транзаксия',
            'order_index' => 1,
        ]);

        $lesson1 = Lesson::create([
            'module_id'    => $module->id,
            'title'        => 'Дарси 1 — Оғози кор',
            'content_type' => 'text',
            'order_index'  => 1,
        ]);

        $lesson2 = Lesson::create([
            'module_id'    => $module->id,
            'title'        => 'Дарси 2 — Коркарди PDO',
            'content_type' => 'text',
            'order_index'  => 2,
        ]);

        // 1. Ҳолати муваффақи транзаксия (Commit): enrollments + lesson_progress
        $firstResp = $this->actingAs($this->student)->post("/student/courses/{$course->id}/enroll");
        $firstResp->assertStatus(302);
        $firstResp->assertSessionHas('success');

        $this->assertDatabaseHas('enrollments', [
            'user_id'   => $this->student->id,
            'course_id' => $course->id,
            'status'    => 'active',
        ]);
        $this->assertDatabaseHas('lesson_progress', [
            'user_id'      => $this->student->id,
            'lesson_id'    => $lesson1->id,
            'is_completed' => false,
        ]);
        $this->assertDatabaseHas('lesson_progress', [
            'user_id'      => $this->student->id,
            'lesson_id'    => $lesson2->id,
            'is_completed' => false,
        ]);

        // 2. Пешгирии номнависии такрорӣ (пахши дубора / HTTP POST дуюм)
        $dupResp = $this->actingAs($this->student)->post("/student/courses/{$course->id}/enroll");
        $dupResp->assertStatus(302);
        $dupResp->assertSessionHas('info');
        $this->assertSame(1, Enrollment::where('user_id', $this->student->id)->where('course_id', $course->id)->count());
        $this->assertSame(2, LessonProgress::where('user_id', $this->student->id)->whereIn('lesson_id', [$lesson1->id, $lesson2->id])->count());

        // 3. Хатогии амалиёти дуюм -> Rollback-и пурраи амалиёти якум (`enrollments`)
        $student2 = User::factory()->create(['role' => 'student']);
        $repo = app(EnrollmentRepository::class);

        $threw = false;
        try {
            // Дохил кардани ID-и такрорӣ дар рӯйхати дарсҳо унвони UNIQUE-ро дар `lesson_progress` вайрон мекунад
            $repo->enrollStudentWithProgress($student2->id, $course->id, [$lesson1->id, $lesson1->id]);
        } catch (PDOException $e) {
            $threw = true;
        }

        $this->assertTrue($threw, 'Амалиёти дуюм бояд PDOException диҳад.');
        $this->assertDatabaseMissing('enrollments', [
            'user_id'   => $student2->id,
            'course_id' => $course->id,
        ]);
        $this->assertSame(0, LessonProgress::where('user_id', $student2->id)->count());
    }

    public function test_tajik_letters_gh_u_j_save_read_and_search_correctly(): void
    {
        $title = 'Омӯзиши Ҷадвалҳо ва Ғояҳои Пойгоҳи Додаҳо';
        $desc = 'Омӯзиши амалии ҷадвалҳо, ғояҳои меъмории релятсионӣ ва ҷустуҷӯи маълумот бо utf8mb4.';

        $store = $this->actingAs($this->admin)->post('/manage/courses', [
            'title'       => $title,
            'description' => $desc,
            'category_id' => $this->catProg->id,
            'status'      => 'published',
        ]);
        $store->assertStatus(302);

        $course = Course::where('title', $title)->firstOrFail();
        $repoCourse = app(CourseRepository::class)->find($course->id);
        $this->assertNotNull($repoCourse);
        $this->assertSame($title, $repoCourse['title']);
        $this->assertSame($desc, $repoCourse['description']);

        foreach (['Ғояҳои', 'Омӯзиши', 'Ҷадвалҳо'] as $queryWord) {
            $catalog = $this->get('/courses?search=' . urlencode($queryWord));
            $catalog->assertOk();
            $catalog->assertSee($title);

            $manage = $this->actingAs($this->admin)->get('/manage/courses?q=' . urlencode($queryWord));
            $manage->assertOk();
            $manage->assertSee($title);
        }
    }
}
