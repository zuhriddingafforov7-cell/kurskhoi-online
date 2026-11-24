<?php

declare(strict_types=1);

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Support\CoursePresenter;
use App\Support\ModelMeta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as LaravelValidator;
use Illuminate\View\View;
use PDOException;
use Src\Repositories\CategoryRepository;
use Src\Repositories\CourseRepository;
use Src\Repositories\UserRepository;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseRepository $courses,
        private readonly CategoryRepository $categories,
        private readonly UserRepository $users,
    ) {
    }

    /**
     * Рӯйхати курсҳои муаллиф тавассути PDO Repository.
     */
    public function index(Request $request): View
    {
        $page = CoursePresenter::parsePage($request);
        $filters = [
            'author_id'   => (int) auth()->id(),
            'q'           => trim((string) ($request->query('search', $request->query('q', '')))),
            'category_id' => CoursePresenter::parsePositiveInt($request->query('category_id')),
            'status'      => trim((string) $request->query('status', '')),
        ];

        $result = $this->courses->paginate($filters, $page, 10, 'latest');
        $hydrated = array_map([CoursePresenter::class, 'hydrateCourse'], $result['items']);
        $courses = CoursePresenter::paginator($hydrated, $result['total'], $result['per_page'], $result['page'], $request);

        return view('author.courses.index', compact('courses'));
    }

    /**
     * Шакли эҷоди курси нав.
     */
    public function create(): View
    {
        $categories = CoursePresenter::hydrateCategoryList($this->categories->all());

        return view('author.courses.create', [
            'categories'  => $categories,
            'old'         => [],
            'fieldErrors' => [],
        ]);
    }

    /**
     * Нигоҳдории курси нав (PDO + HTTP 422 дар хатои validation).
     */
    public function store(Request $request): RedirectResponse|Response|JsonResponse
    {
        $validated = CoursePresenter::validateCourseForm(
            request: $request,
            courses: $this->courses,
            categories: $this->categories,
            users: $this->users,
            isAdmin: false,
            currentUserId: (int) auth()->id(),
            ignoreCourseId: null,
            defaultStatus: Course::STATUS_PENDING,
            minDescriptionLength: 5
        );

        $errors = $validated['errors'];

        $extraValidator = LaravelValidator::make($request->all(), [
            'level'       => ['nullable', 'in:beginner,intermediate,advanced'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'cover_image.image' => 'Сурати муқова бояд тасвир бошад.',
            'cover_image.max'   => 'Ҳаҷми сурати муқова набояд аз 5 МБ зиёд бошад.',
        ]);

        if ($extraValidator->fails()) {
            foreach ($extraValidator->errors()->toArray() as $field => $messages) {
                $errors[$field] = array_values(array_unique(array_merge($errors[$field] ?? [], $messages)));
            }
        }

        if ($errors !== []) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Лутфан хатогиҳои шаклро ислоҳ кунед.',
                    'errors'  => $errors,
                ], 422);
            }

            $categories = CoursePresenter::hydrateCategoryList($this->categories->all());
            $old = $validated['old'];
            $old['level'] = (string) $request->input('level', 'beginner');

            return response()->view('author.courses.create', [
                'categories'  => $categories,
                'old'         => $old,
                'fieldErrors' => $errors,
            ], 422);
        }

        $data = $validated['data'];
        $courseId = $this->courses->create($data);

        $level = trim((string) $request->input('level', ''));
        if ($level !== '') {
            ModelMeta::set('course', $courseId, 'level', $level);
        }

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers/' . $courseId, 'local');
            ModelMeta::set('course', $courseId, 'cover_image', $coverPath);
        }

        $msg = $data['status'] === Course::STATUS_DRAFT
            ? 'Курс ҳамчун лоиҳа (Draft) захира карда шуд.'
            : 'Курс бо муваффақият сохта шуд ва барои тасдиқ фиристода шуд!';

        return redirect()
            ->route('author.courses.index')
            ->with('success', $msg);
    }

    /**
     * Нишон додани курси мушаххас, сохтор ва омори пешрафти донишҷӯён (FR-11).
     */
    public function show(Course $course): View
    {
        $this->authorizeOwner($course);

        $course->load([
            'modules.lessons.assignment.submissions.student',
            'modules.lessons.materials',
            'category',
            'enrollments.user',
            'enrollments.certificate',
        ]);

        $submissions = collect();
        foreach ($course->modules as $module) {
            foreach ($module->lessons as $lesson) {
                if ($lesson->assignment) {
                    $subs = $lesson->assignment->submissions
                        ->whereIn('status', ['submitted', 'pending']);
                    $submissions = $submissions->merge($subs);
                }
            }
        }

        $enrollments = $course->enrollments->map(function ($enrollment) use ($course) {
            if ($enrollment->user) {
                $enrollment->progress = $enrollment->user->progressInCourse($course);
            } else {
                $enrollment->progress = 0;
            }

            return $enrollment;
        });

        return view('author.courses.show', compact('course', 'submissions', 'enrollments'));
    }

    /**
     * Шакли таҳрири курс.
     */
    public function edit(Course $course): View
    {
        $this->authorizeOwner($course);
        $categories = CoursePresenter::hydrateCategoryList($this->categories->all());

        return view('author.courses.edit', [
            'course'      => $course,
            'categories'  => $categories,
            'old'         => [],
            'fieldErrors' => [],
        ]);
    }

    /**
     * Навсозии курс (PDO + HTTP 422 дар хатои validation).
     */
    public function update(Request $request, Course $course): RedirectResponse|Response|JsonResponse
    {
        $this->authorizeOwner($course);

        $validated = CoursePresenter::validateCourseForm(
            request: $request,
            courses: $this->courses,
            categories: $this->categories,
            users: $this->users,
            isAdmin: false,
            currentUserId: (int) auth()->id(),
            ignoreCourseId: (int) $course->id,
            defaultStatus: Course::STATUS_PENDING,
            minDescriptionLength: 5
        );

        $errors = $validated['errors'];

        $extraValidator = LaravelValidator::make($request->all(), [
            'level'       => ['nullable', 'in:beginner,intermediate,advanced'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'cover_image.image' => 'Сурати муқова бояд тасвир бошад.',
            'cover_image.max'   => 'Ҳаҷми сурати муқова набояд аз 5 МБ зиёд бошад.',
        ]);

        if ($extraValidator->fails()) {
            foreach ($extraValidator->errors()->toArray() as $field => $messages) {
                $errors[$field] = array_values(array_unique(array_merge($errors[$field] ?? [], $messages)));
            }
        }

        if ($errors !== []) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Лутфан хатогиҳои шаклро ислоҳ кунед.',
                    'errors'  => $errors,
                ], 422);
            }

            $categories = CoursePresenter::hydrateCategoryList($this->categories->all());
            $old = $validated['old'];
            $old['level'] = (string) $request->input('level', $course->level);

            return response()->view('author.courses.edit', [
                'course'      => $course,
                'categories'  => $categories,
                'old'         => $old,
                'fieldErrors' => $errors,
            ], 422);
        }

        $updateData = [
            'title'       => $validated['data']['title'],
            'description' => $validated['data']['description'],
            'category_id' => $validated['data']['category_id'],
        ];

        // Агар нашр шуда бошад ва таҳрир карда шавад — дубора ба модератсия (pending)
        if ($course->status === Course::STATUS_PUBLISHED) {
            $updateData['status'] = Course::STATUS_PENDING;
        } elseif ($request->filled('status')) {
            $updateData['status'] = $validated['data']['status'];
        }

        $this->courses->update((int) $course->id, $updateData);

        $level = trim((string) $request->input('level', ''));
        if ($level !== '') {
            ModelMeta::set('course', $course->id, 'level', $level);
        }

        if ($request->hasFile('cover_image')) {
            $oldCover = $course->cover_image;
            $coverPath = $request->file('cover_image')->store('covers/' . $course->id, 'local');
            if ($oldCover) {
                Storage::disk('local')->delete($oldCover);
                Storage::disk('public')->delete($oldCover);
            }
            ModelMeta::set('course', $course->id, 'cover_image', $coverPath);
        }

        return redirect()
            ->route('author.courses.show', $course)
            ->with('success', 'Курс бо муваффақият навсозӣ шуд!');
    }

    /**
     * Ирсоли курси draft ё rejected ба модератсия (pending).
     */
    public function submitForReview(Course $course): RedirectResponse
    {
        $this->authorizeOwner($course);

        if ($course->status === Course::STATUS_PUBLISHED) {
            return back()->with('info', 'Ин курс аллакай нашр шудааст.');
        }

        $this->courses->update((int) $course->id, ['status' => Course::STATUS_PENDING]);
        ModelMeta::set('course', $course->id, 'rejection_reason', null);

        return back()->with('success', 'Курс барои санҷиш ва модератсия фиристода шуд!');
    }

    /**
     * Нест кардани курс бо ҳифзи маълумоти вобаста (Foreign Key).
     */
    public function destroy(Course $course): RedirectResponse
    {
        $this->authorizeOwner($course);

        $deps = $this->courses->countDependencies((int) $course->id);
        if ($deps['enrollments'] > 0) {
            Log::warning('Несткунии курс аз кабинети муаллиф боздошта шуд: донишҷӯён мавҷуданд.', [
                'course_id'   => $course->id,
                'enrollments' => $deps['enrollments'],
            ]);

            return back()->with('error', 'Курсе, ки донишҷӯёни номнависшуда дорад, нест карда намешавад.');
        }

        if ($deps['modules'] > 0) {
            Log::warning('Несткунии курс аз кабинети муаллиф боздошта шуд: модулҳо мавҷуданд.', [
                'course_id' => $course->id,
                'modules'   => $deps['modules'],
            ]);

            return back()->with('error', 'Курсро нест кардан мумкин нест, зеро он дорои модулҳои таълимӣ мебошад.');
        }

        try {
            $this->courses->delete((int) $course->id);
        } catch (PDOException $e) {
            Log::warning('Хатои Foreign Key ҳангоми несткунии курс.', [
                'course_id' => $course->id,
                'code'      => $e->getCode(),
                'error'     => $e->getMessage(),
            ]);

            return back()->with('error', 'Нест кардани курс имконнопазир аст, зеро сабтҳои вобаста мавҷуданд.');
        }

        return redirect()
            ->route('author.courses.index')
            ->with('success', 'Курс нест карда шуд.');
    }

    private function authorizeOwner(Course $course): void
    {
        if ((int) $course->author_id !== (int) auth()->id()) {
            abort(403, 'Шумо ба ин курс дастрасӣ надоред.');
        }
    }
}
