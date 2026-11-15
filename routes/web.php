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
use App\Http\Controllers\Student\CertificateController as StudentCertificateController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\LessonController as StudentLessonController;
use App\Http\Controllers\Student\SubmissionController as StudentSubmissionController;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $featuredCourses = Course::published()
        ->with(['author', 'category'])
        ->withCount('enrollments')
        ->latest()
        ->take(6)
        ->get();

    $categories = Category::withCount(['courses' => fn ($q) => $q->where('status', 'published')])->get();

    return view('welcome', compact('featuredCourses', 'categories'));
})->name('home');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

/*
|--------------------------------------------------------------------------
| Guest Routes (Аутентификатсия ва барқарорсозии парол)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes (Нақши: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

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
    Route::put('lessons/{lesson}', [AuthorLessonController::class, 'update'])->name('lessons.update');
    Route::delete('lessons/{lesson}', [AuthorLessonController::class, 'destroy'])->name('lessons.destroy');
    Route::delete('materials/{material}', [AuthorLessonController::class, 'destroyMaterial'])->name('materials.destroy');

    // Супоришҳо
    Route::get('lessons/{lesson}/assignments/create', [AuthorAssignmentController::class, 'create'])->name('lessons.assignments.create');
    Route::post('lessons/{lesson}/assignments', [AuthorAssignmentController::class, 'store'])->name('lessons.assignments.store');
    Route::get('assignments/{assignment}/edit', [AuthorAssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('assignments/{assignment}', [AuthorAssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('assignments/{assignment}', [AuthorAssignmentController::class, 'destroy'])->name('assignments.destroy');

    // Санҷиши корҳои муҳассилин
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
