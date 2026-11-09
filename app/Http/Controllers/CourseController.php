<?php

namespace App\Http\Controllers;

use App\Repositories\CourseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct(
        protected CourseRepository $courseRepository = new CourseRepository()
    ) {}

    /**
     * Display a listing of courses authored by the authenticated author.
     */
    public function index(Request $request): JsonResponse|Response
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Барои дидани курсҳо бояд аввал ба система ворид шавед.');
        }

        if ($user->role !== 'author') {
            abort(403, 'Фақат муаллифон (author) ба ин саҳифа дастрасӣ доранд.');
        }

        $courses = $user->courses()->latest()->get();

        if ($request->wantsJson()) {
            return response()->json([
                'courses' => $courses,
            ]);
        }

        return response('Саҳифаи курсҳои муаллиф (author/courses)', 200);
    }

    /**
     * Store a newly created course submitted for moderation.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        // 1. Authenticated user check
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Барои ирсоли курс бояд аввал ба система ворид шавед.');
        }

        // 2. Author role check
        if ($user->role !== 'author') {
            abort(403, 'Фақат муаллифон (author) ҳуқуқи пешниҳоди курсро доранд.');
        }

        // 3. Validation of required and optional fields
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        // 4. Set author_id from authenticated user (not from client input)
        $validated['author_id'] = $user->id;

        // 5. Create or submit course via CourseRepository with status='pending'
        $course = $this->courseRepository->createOrSubmit($validated, 'pending');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Курс бомуваффақият сабт шуда, барои модератсия ирсол гардид.',
                'course' => $course,
            ], 201);
        }

        return redirect()->to('/author/courses')->with('success', 'Курс барои модератсия ирсол гардид.');
    }
}
