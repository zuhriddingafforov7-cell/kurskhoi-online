<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Author\AssignmentController as AuthorAssignmentController;
use App\Http\Controllers\Author\CourseController as AuthorCourseController;
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;
use App\Http\Controllers\Author\LessonController as AuthorLessonController;
use App\Http\Controllers\Author\ModuleController as AuthorModuleController;
use App\Http\Controllers\Author\SubmissionController as AuthorSubmissionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialAccessController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\CertificateController as StudentCertificateController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\LessonController as StudentLessonController;
use App\Http\Controllers\Student\SubmissionController as StudentSubmissionController;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $featuredCourses = Course::published()
        ->with(['author', 'category'])
        ->withCount(['enrollments', 'modules'])
        ->latest()
        ->take(6)
        ->get();

    $popularCourses = Course::published()
        ->with(['author', 'category'])
        ->withCount(['enrollments', 'modules'])
        ->orderByDesc('enrollments_count')
        ->latest()
        ->take(3)
        ->get();

    $categories = Category::withCount(['courses' => fn ($q) => $q->where('status', 'published')])
        ->orderBy('title')
        ->get();

    $instructors = User::where('role', 'author')
        ->whereHas('courses', fn ($q) => $q->where('status', 'published'))
        ->withCount(['courses' => fn ($q) => $q->where('status', 'published')])
        ->orderByDesc('courses_count')
        ->take(4)
        ->get();

    $stats = [
        'courses_count'      => Course::published()->count(),
        'categories_count'   => $categories->count(),
        'students_count'     => User::where('role', 'student')->count(),
        'certificates_count' => Certificate::count(),
    ];

    return view('welcome', compact('featuredCourses', 'popularCourses', 'categories', 'instructors', 'stats'));
})->name('home');

Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['tg', 'ru'], true)) {
        session(['locale' => $locale]);
        cookie()->queue('locale', $locale, 60 * 24 * 30);
    }

    return back()->withCookie(cookie('locale', in_array($locale, ['tg', 'ru'], true) ? $locale : 'tg', 60 * 24 * 30));
})->name('locale.switch');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{course}/cover', [MaterialAccessController::class, 'courseCover'])->name('courses.cover');

/*
|--------------------------------------------------------------------------
| Guest Routes (Аутентификатсия ва барқарорсозии парол)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('throttle:30,1');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:30,1');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email')->middleware('throttle:20,1');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update')->middleware('throttle:20,1');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Профил, Баромад ва Файлҳои муҳофизатшуда)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Профили корбар
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Дастрасии муҳофизатшуда ба маводҳо ва файлҳои супориш
    Route::get('/materials/{material}/stream', [MaterialAccessController::class, 'streamMaterial'])->name('materials.stream');
    Route::get('/materials/{material}/download', [MaterialAccessController::class, 'downloadMaterial'])->name('materials.download');
    Route::get('/lessons/{lesson}/attachment', [MaterialAccessController::class, 'downloadLessonAttachment'])->name('lessons.attachment');
    Route::get('/submissions/{submission}/download', [StudentSubmissionController::class, 'download'])->name('submissions.download');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes (Нақши: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/certificates/export-csv', [AdminDashboardController::class, 'exportGraduatesCsv'])->name('certificates.export');

    // Корбарон
    Route::resource('users', AdminUserController::class)->only(['index', 'show', 'edit', 'update']);

    // Категорияҳо
    Route::resource('categories', AdminCategoryController::class)->except(['show']);

    // Модератсияи курсҳо
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
    Route::patch('/courses/{course}/approve', [AdminCourseController::class, 'approve'])->name('courses.approve');
    Route::patch('/courses/{course}/reject', [AdminCourseController::class, 'reject'])->name('courses.reject');
});

/*
|--------------------------------------------------------------------------
| Author Protected Routes (Нақши: author)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:author'])->prefix('author')->name('author.')->group(function () {
    Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');

    // Курсҳо
    Route::resource('courses', AuthorCourseController::class);
    Route::patch('courses/{course}/submit', [AuthorCourseController::class, 'submitForReview'])->name('courses.submit');

    // Модулҳо (вобаста ба курс)
    Route::get('courses/{course}/modules/create', [AuthorModuleController::class, 'create'])->name('courses.modules.create');
    Route::post('courses/{course}/modules', [AuthorModuleController::class, 'store'])->name('courses.modules.store');
    Route::get('modules/{module}/edit', [AuthorModuleController::class, 'edit'])->name('modules.edit');
    Route::put('modules/{module}', [AuthorModuleController::class, 'update'])->name('modules.update');
    Route::delete('modules/{module}', [AuthorModuleController::class, 'destroy'])->name('modules.destroy');

    // Дарсҳо (вобаста ба модул)
    Route::get('modules/{module}/lessons/create', [AuthorLessonController::class, 'create'])->name('modules.lessons.create');
    Route::post('modules/{module}/lessons', [AuthorLessonController::class, 'store'])->name('modules.lessons.store');
    Route::get('lessons/{lesson}/edit', [AuthorLessonController::class, 'edit'])->name('lessons.edit');
    Route::get('lessons/{lesson}/preview', [AuthorLessonController::class, 'preview'])->name('lessons.preview');
    Route::put('lessons/{lesson}', [AuthorLessonController::class, 'update'])->name('lessons.update');
    Route::delete('lessons/{lesson}', [AuthorLessonController::class, 'destroy'])->name('lessons.destroy');
    Route::put('materials/{material}', [AuthorLessonController::class, 'updateMaterial'])->name('materials.update');
    Route::delete('materials/{material}', [AuthorLessonController::class, 'destroyMaterial'])->name('materials.destroy');

    // Супоришҳо
    Route::get('lessons/{lesson}/assignments/create', [AuthorAssignmentController::class, 'create'])->name('lessons.assignments.create');
    Route::post('lessons/{lesson}/assignments', [AuthorAssignmentController::class, 'store'])->name('lessons.assignments.store');
    Route::get('assignments/{assignment}/edit', [AuthorAssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('assignments/{assignment}', [AuthorAssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('assignments/{assignment}', [AuthorAssignmentController::class, 'destroy'])->name('assignments.destroy');

    // Санҷиши корҳои донишҷӯён
    Route::get('submissions', [AuthorSubmissionController::class, 'index'])->name('submissions.index');
    Route::patch('submissions/{submission}/grade', [AuthorSubmissionController::class, 'grade'])->name('submissions.grade');
});

/*
|--------------------------------------------------------------------------
| Student Protected Routes (Нақши: student)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/my-courses', [StudentCourseController::class, 'myCourses'])->name('my-courses');

    Route::get('/lessons/{lesson}', [StudentLessonController::class, 'show'])->name('lessons.show');
    Route::post('/lessons/{lesson}/complete', [StudentLessonController::class, 'markComplete'])->name('lessons.complete');

    Route::get('/assignments/{assignment}', [StudentSubmissionController::class, 'show'])->name('assignments.show');
    Route::post('/assignments/{assignment}/submit', [StudentSubmissionController::class, 'store'])->name('assignments.submit');

    Route::get('/certificates/{certificate}/download', [StudentCertificateController::class, 'download'])->name('certificates.download');
});
