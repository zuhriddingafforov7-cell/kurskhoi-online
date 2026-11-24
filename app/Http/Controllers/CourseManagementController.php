<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use App\Support\CoursePresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PDOException;
use Src\Repositories\CategoryRepository;
use Src\Repositories\CourseRepository;
use Src\Repositories\UserRepository;

/**
 * Контроллери пурраи CRUD барои идоракунии курсҳо тавассути PDO Repository (Лабораторияи №13).
 *
 * Ҳуқуқи дастрасӣ:
 * - Маъмур (`admin`): идоракунии ҳамаи курсҳо ва интихоби ҳолату муаллиф;
 * - Муаллим (`author`): эҷод ва идоракунии танҳо курсҳои худ (`author_id = auth()->id()`).
 */
class CourseManagementController extends Controller
{
    public function __construct(
        private readonly CourseRepository $courses,
        private readonly CategoryRepository $categories,
        private readonly UserRepository $users,
    ) {
    }

    /**
     * Рӯйхати курсҳо бо ҷустуҷӯ, ду+ филтр, JOIN ва пагинация (10 сабт дар як саҳифа).
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $isAdmin = $user->isAdmin();

        $page = CoursePresenter::parsePage($request);
        $q = trim((string) ($request->query('q', $request->query('search', ''))));
        $categoryId = CoursePresenter::parsePositiveInt($request->query('category_id'));
        $statusRaw = trim((string) $request->query('status', ''));
        $status = in_array($statusRaw, CourseRepository::ALLOWED_STATUSES, true) ? $statusRaw : null;
        $sortRaw = trim((string) $request->query('sort', 'latest'));
        $sort = in_array($sortRaw, ['latest', 'oldest', 'title', 'popular'], true) ? $sortRaw : 'latest';

        // Назорати қатъии author_id дар сервер: муаллим танҳо курсҳои худро мебинад
        $authorId = $isAdmin
            ? CoursePresenter::parsePositiveInt($request->query('author_id'))
            : (int) $user->id;

        $filters = [
            'q'           => $q,
            'category_id' => $categoryId,
            'status'      => $status,
            'author_id'   => $authorId,
        ];

        $result = $this->courses->paginate($filters, $page, 10, $sort);
        $paginator = CoursePresenter::paginator(
            $result['items'],
            $result['total'],
            $result['per_page'],
            $result['page'],
            $request
        );

        $categories = CoursePresenter::hydrateCategoryList($this->categories->all());
        $authors = $isAdmin ? CoursePresenter::hydrateUserList($this->users->courseManagers()) : [];
        $statusCounts = $this->courses->countByStatus($isAdmin ? null : (int) $user->id);

        return view('manage.courses.index', [
            'courses'      => $paginator,
            'pagination'   => $result,
            'categories'   => $categories,
            'authors'      => $authors,
            'statusCounts' => $statusCounts,
            'isAdmin'      => $isAdmin,
            'filters'      => [
                'q'           => $q,
                'category_id' => $categoryId,
                'status'      => $status,
                'author_id'   => $isAdmin ? $authorId : null,
                'sort'        => $sort,
            ],
        ]);
    }

    /**
     * Намоиши маълумоти пурраи як курс бо номи категория ва муаллиф (JOIN).
     */
    public function show(Request $request, string $id): View
    {
        $courseId = CoursePresenter::parsePositiveInt($id);
        if ($courseId === null) {
            abort(404);
        }

        $course = $this->courses->find($courseId);
        if ($course === null) {
            abort(404);
        }

        $this->authorizeCourseAccess($request, $course);
        $dependencies = $this->courses->countDependencies($courseId);

        return view('manage.courses.show', [
            'course'       => $course,
            'dependencies' => $dependencies,
            'isAdmin'      => $request->user()->isAdmin(),
        ]);
    }

    /**
     * Шакли эҷоди курси нав.
     */
    public function create(Request $request): View
    {
        $isAdmin = $request->user()->isAdmin();
        $categories = CoursePresenter::hydrateCategoryList($this->categories->all());
        $authors = $isAdmin ? CoursePresenter::hydrateUserList($this->users->courseManagers()) : [];

        return view('manage.courses.create', [
            'categories'  => $categories,
            'authors'     => $authors,
            'isAdmin'     => $isAdmin,
            'old'         => [
                'title'       => '',
                'description' => '',
                'category_id' => '',
                'status'      => Course::STATUS_PENDING,
                'author_id'   => (string) $request->user()->id,
            ],
            'fieldErrors' => [],
        ]);
    }

    /**
     * Сабти курси нав (POST).
     * Ҳангоми хатои validation HTTP 422 бо нигоҳдории маълумоти воридшуда бармегардонад.
     * Ҳангоми муваффақият HTTP 302 (PRG) ба саҳифаи курс равона мекунад.
     */
    public function store(Request $request): RedirectResponse|Response|JsonResponse
    {
        $user = $request->user();
        $isAdmin = $user->isAdmin();

        $validated = CoursePresenter::validateCourseForm(
            request: $request,
            courses: $this->courses,
            categories: $this->categories,
            users: $this->users,
            isAdmin: $isAdmin,
            currentUserId: (int) $user->id,
            ignoreCourseId: null,
            defaultStatus: Course::STATUS_PENDING,
            minDescriptionLength: 10
        );

        if ($validated['errors'] !== []) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Лутфан хатогиҳои шаклро ислоҳ кунед.',
                    'errors'  => $validated['errors'],
                ], 422);
            }

            $categories = CoursePresenter::hydrateCategoryList($this->categories->all());
            $authors = $isAdmin ? CoursePresenter::hydrateUserList($this->users->courseManagers()) : [];

            return response()->view('manage.courses.create', [
                'categories'  => $categories,
                'authors'     => $authors,
                'isAdmin'     => $isAdmin,
                'old'         => $validated['old'],
                'fieldErrors' => $validated['errors'],
            ], 422);
        }

        $newCourseId = $this->courses->create($validated['data']);

        return redirect()
            ->route('manage.courses.show', $newCourseId)
            ->with('success', 'Курси нав бо муваффақият сабт шуд!');
    }

    /**
     * Шакли таҳрири курси мавҷуда.
     */
    public function edit(Request $request, string $id): View
    {
        $courseId = CoursePresenter::parsePositiveInt($id);
        if ($courseId === null) {
            abort(404);
        }

        $course = $this->courses->find($courseId);
        if ($course === null) {
            abort(404);
        }

        $this->authorizeCourseAccess($request, $course);

        $isAdmin = $request->user()->isAdmin();
        $categories = CoursePresenter::hydrateCategoryList($this->categories->all());
        $authors = $isAdmin ? CoursePresenter::hydrateUserList($this->users->courseManagers()) : [];

        return view('manage.courses.edit', [
            'course'      => $course,
            'categories'  => $categories,
            'authors'     => $authors,
            'isAdmin'     => $isAdmin,
            'old'         => [
                'title'       => (string) $course['title'],
                'description' => (string) $course['description'],
                'category_id' => (string) ($course['category_id'] ?? ''),
                'status'      => (string) $course['status'],
                'author_id'   => (string) $course['author_id'],
            ],
            'fieldErrors' => [],
        ]);
    }

    /**
     * Навсозии курси мавҷуда (POST).
     * Ҳангоми хатои validation (аз ҷумла такрори ном ба истиснои худи курс) HTTP 422 бармегардонад.
     */
    public function update(Request $request, string $id): RedirectResponse|Response|JsonResponse
    {
        $courseId = CoursePresenter::parsePositiveInt($id);
        if ($courseId === null) {
            abort(404);
        }

        $course = $this->courses->find($courseId);
        if ($course === null) {
            abort(404);
        }

        $this->authorizeCourseAccess($request, $course);

        $user = $request->user();
        $isAdmin = $user->isAdmin();

        $defaultStatus = $isAdmin
            ? (string) $course['status']
            : Course::STATUS_PENDING;

        $validated = CoursePresenter::validateCourseForm(
            request: $request,
            courses: $this->courses,
            categories: $this->categories,
            users: $this->users,
            isAdmin: $isAdmin,
            currentUserId: $isAdmin ? (int) $course['author_id'] : (int) $user->id,
            ignoreCourseId: $courseId,
            defaultStatus: $defaultStatus,
            minDescriptionLength: 10
        );

        if ($validated['errors'] !== []) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Лутфан хатогиҳои шаклро ислоҳ кунед.',
                    'errors'  => $validated['errors'],
                ], 422);
            }

            $categories = CoursePresenter::hydrateCategoryList($this->categories->all());
            $authors = $isAdmin ? CoursePresenter::hydrateUserList($this->users->courseManagers()) : [];

            return response()->view('manage.courses.edit', [
                'course'      => $course,
                'categories'  => $categories,
                'authors'     => $authors,
                'isAdmin'     => $isAdmin,
                'old'         => $validated['old'],
                'fieldErrors' => $validated['errors'],
            ], 422);
        }

        $payload = $validated['data'];
        if (!$isAdmin) {
            // Муаллим наметавонад тавассути таҳрир соҳибро иваз ё курсро мустақиман нашр кунад
            $payload['author_id'] = (int) $course['author_id'];
            if ($course['status'] === Course::STATUS_PUBLISHED) {
                $payload['status'] = Course::STATUS_PENDING;
            }
        }

        $this->courses->update($courseId, $payload);

        return redirect()
            ->route('manage.courses.show', $courseId)
            ->with('success', 'Маълумоти курс бо муваффақият навсозӣ шуд!');
    }

    /**
     * Нест кардани курс бо POST ва коркарди амни Foreign Key (SQLSTATE 23000 / error 1451).
     */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        $courseId = CoursePresenter::parsePositiveInt($id);
        if ($courseId === null) {
            abort(404);
        }

        $course = $this->courses->find($courseId);
        if ($course === null) {
            abort(404);
        }

        $this->authorizeCourseAccess($request, $course);

        $deps = $this->courses->countDependencies($courseId);
        if ($deps['enrollments'] > 0) {
            Log::warning('Несткунии курс бо сабаби маҳдудияти Foreign Key (enrollments) боздошта шуд.', [
                'course_id'         => $courseId,
                'user_id'           => $request->user()->id,
                'enrollments_count' => $deps['enrollments'],
            ]);

            return redirect()
                ->route('manage.courses.index')
                ->with('error', "Курси «{$course['title']}»-ро нест кардан мумкин нест: ба он {$deps['enrollments']} донишҷӯ номнавис шудааст. Маълумоти таълимӣ ва шаҳодатномаҳо ҳифз шуданд.");
        }

        if ($deps['modules'] > 0) {
            Log::warning('Несткунии курс бо сабаби вуҷуд доштани модулҳо боздошта шуд.', [
                'course_id'     => $courseId,
                'user_id'       => $request->user()->id,
                'modules_count' => $deps['modules'],
            ]);

            return redirect()
                ->route('manage.courses.index')
                ->with('error', "Курси «{$course['title']}»-ро нест кардан мумкин нест: он дорои {$deps['modules']} модули таълимӣ мебошад. Аввал модулҳоро нест кунед.");
        }

        try {
            $deleted = $this->courses->delete($courseId);
        } catch (PDOException $e) {
            $driverCode = (int) ($e->errorInfo[1] ?? 0);
            if ($e->getCode() === '23000' || $driverCode === 1451) {
                Log::warning('Хатои Foreign Key (SQLSTATE 23000 / 1451) ҳангоми нест кардани курс.', [
                    'course_id'   => $courseId,
                    'sqlstate'    => $e->getCode(),
                    'driver_code' => $driverCode,
                    'error'       => $e->getMessage(),
                ]);

                return redirect()
                    ->route('manage.courses.index')
                    ->with('error', "Курси «{$course['title']}»-ро нест кардан мумкин нест, зеро сабтҳои вобаста дар пойгоҳи дода мавҷуданд.");
            }

            throw $e;
        }

        if (!$deleted) {
            return redirect()
                ->route('manage.courses.index')
                ->with('error', 'Курс барои нест кардан ёфт нашуд.');
        }

        return redirect()
            ->route('manage.courses.index')
            ->with('success', "Курси «{$course['title']}» бо муваффақият нест карда шуд.");
    }

    /**
     * @param  array<string, mixed>  $course
     */
    private function authorizeCourseAccess(Request $request, array $course): void
    {
        $user = $request->user();
        if ($user->isAdmin()) {
            return;
        }

        if ((int) $course['author_id'] !== (int) $user->id) {
            abort(403, 'Шумо ба идоракунии ин курс иҷозат надоред.');
        }
    }
}
