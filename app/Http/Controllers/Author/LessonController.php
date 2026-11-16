<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonMaterial;
use App\Models\Module;
use App\Support\ModelMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function create(Module $module): View
    {
        $this->authorizeModule($module);

        return view('author.lessons.create', compact('module'));
    }

    public function store(Request $request, Module $module): RedirectResponse
    {
        $this->authorizeModule($module);

        $data = $request->validate([
            'title'                 => ['required', 'string', 'max:255'],
            'content_type'          => ['required', 'in:text,video,file'],
            'content_text'          => ['nullable', 'string'],
            'video_url'             => ['nullable', 'url', 'max:255'],
            'file'                  => ['nullable', 'file', 'max:102400', 'mimes:mp4,webm,doc,docx,pdf,jpg,jpeg,png,webp,ppt,pptx'],
            'order_index'           => ['required', 'integer', 'min:1'],
            'duration_minutes'      => ['nullable', 'integer', 'min:1', 'max:600'],
            'status'                => ['nullable', 'in:draft,published'],
            'materials'             => ['nullable', 'array'],
            'materials.*'           => ['file', 'max:102400', 'mimes:mp4,webm,doc,docx,pdf,jpg,jpeg,png,webp,ppt,pptx'],
            'material_titles'       => ['nullable', 'array'],
            'material_titles.*'     => ['nullable', 'string', 'max:255'],
            'material_descriptions' => ['nullable', 'array'],
            'material_descriptions.*' => ['nullable', 'string', 'max:1000'],
            'link_urls'             => ['nullable', 'array'],
            'link_urls.*'           => ['nullable', 'url', 'max:500'],
            'link_titles'           => ['nullable', 'array'],
            'link_titles.*'         => ['nullable', 'string', 'max:255'],
            'link_types'            => ['nullable', 'array'],
            'link_types.*'          => ['nullable', 'in:link,video'],
            'link_descriptions'     => ['nullable', 'array'],
            'link_descriptions.*'   => ['nullable', 'string', 'max:1000'],
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('lessons/' . $module->id, 'local');
        }

        $lesson = Lesson::create([
            'module_id'    => $module->id,
            'title'        => $data['title'],
            'content_type' => $data['content_type'],
            'body_text'    => $data['content_text'] ?? null,
            'video_url'    => $data['video_url'] ?? null,
            'file_path'    => $filePath,
            'order_index'  => $data['order_index'],
        ]);

        if (isset($data['duration_minutes'])) {
            ModelMeta::set('lesson', $lesson->id, 'duration_minutes', (int) $data['duration_minutes']);
        }
        ModelMeta::set('lesson', $lesson->id, 'status', $data['status'] ?? 'published');

        $this->handleUploadedMaterials($request, $lesson);
        $this->handleExternalLinks($request, $lesson);

        return redirect()
            ->route('author.courses.show', $module->course)
            ->with('success', 'Дарс ва маводҳои таълимӣ илова шуданд!');
    }

    public function edit(Lesson $lesson): View
    {
        $module = $lesson->module;
        $this->authorizeModule($module);
        $lesson->load('materials');

        return view('author.lessons.edit', compact('lesson', 'module'));
    }

    public function preview(Lesson $lesson): View
    {
        $module = $lesson->module;
        $this->authorizeModule($module);

        $course = $module->course->load(['modules.lessons.assignment']);
        $lesson->load(['assignment', 'materials']);
        $progress = null;
        $submission = null;
        $enrollment = null;
        $completedLessonIds = [];
        $isPreview = true;

        return view('student.lessons.show', compact(
            'lesson',
            'module',
            'course',
            'progress',
            'submission',
            'enrollment',
            'completedLessonIds',
            'isPreview'
        ));
    }

    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $module = $lesson->module;
        $this->authorizeModule($module);

        $data = $request->validate([
            'title'                 => ['required', 'string', 'max:255'],
            'content_type'          => ['required', 'in:text,video,file'],
            'content_text'          => ['nullable', 'string'],
            'video_url'             => ['nullable', 'url', 'max:255'],
            'file'                  => ['nullable', 'file', 'max:102400', 'mimes:mp4,webm,doc,docx,pdf,jpg,jpeg,png,webp,ppt,pptx'],
            'order_index'           => ['required', 'integer', 'min:1'],
            'duration_minutes'      => ['nullable', 'integer', 'min:1', 'max:600'],
            'status'                => ['nullable', 'in:draft,published'],
            'materials'             => ['nullable', 'array'],
            'materials.*'           => ['file', 'max:102400', 'mimes:mp4,webm,doc,docx,pdf,jpg,jpeg,png,webp,ppt,pptx'],
            'material_titles'       => ['nullable', 'array'],
            'material_titles.*'     => ['nullable', 'string', 'max:255'],
            'material_descriptions' => ['nullable', 'array'],
            'material_descriptions.*' => ['nullable', 'string', 'max:1000'],
            'link_urls'             => ['nullable', 'array'],
            'link_urls.*'           => ['nullable', 'url', 'max:500'],
            'link_titles'           => ['nullable', 'array'],
            'link_titles.*'         => ['nullable', 'string', 'max:255'],
            'link_types'            => ['nullable', 'array'],
            'link_types.*'          => ['nullable', 'in:link,video'],
            'link_descriptions'     => ['nullable', 'array'],
            'link_descriptions.*'   => ['nullable', 'string', 'max:1000'],
        ]);

        $updateData = [
            'title'        => $data['title'],
            'content_type' => $data['content_type'],
            'body_text'    => $data['content_text'] ?? null,
            'video_url'    => $data['video_url'] ?? null,
            'order_index'  => $data['order_index'],
        ];

        if ($request->hasFile('file')) {
            $newFilePath = $request->file('file')->store('lessons/' . $module->id, 'local');
            if ($lesson->file_path) {
                Storage::disk('local')->delete($lesson->file_path);
                Storage::disk('public')->delete($lesson->file_path);
            }
            $updateData['file_path'] = $newFilePath;
        }

        $lesson->update($updateData);

        ModelMeta::set('lesson', $lesson->id, 'duration_minutes', isset($data['duration_minutes']) ? (int) $data['duration_minutes'] : null);
        if (isset($data['status'])) {
            ModelMeta::set('lesson', $lesson->id, 'status', $data['status']);
        }

        $this->handleUploadedMaterials($request, $lesson);
        $this->handleExternalLinks($request, $lesson);

        return redirect()
            ->route('author.courses.show', $module->course)
            ->with('success', 'Дарс бо муваффақият навсозӣ шуд!');
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        $module = $lesson->module;
        $this->authorizeModule($module);

        $materialPaths = $lesson->materials->pluck('file_path')->all();
        $lessonFilePath = $lesson->file_path;

        try {
            $lesson->delete();
        } catch (\Throwable $e) {
            return back()->with('error', 'Дарсро нест кардан имконнопазир аст, зеро супоришҳои санҷидашуда дорад.');
        }

        foreach ($materialPaths as $p) {
            if ($p && !str_starts_with($p, 'http')) {
                Storage::disk('local')->delete($p);
                Storage::disk('public')->delete($p);
            }
        }

        if ($lessonFilePath) {
            Storage::disk('local')->delete($lessonFilePath);
            Storage::disk('public')->delete($lessonFilePath);
        }

        return redirect()
            ->route('author.courses.show', $module->course)
            ->with('success', 'Дарс нест карда шуд.');
    }

    /**
     * Таҳрири ном, тавсиф ва тартиби маводи мавҷуда.
     */
    public function updateMaterial(Request $request, LessonMaterial $material): RedirectResponse
    {
        $lesson = $material->lesson;
        $this->authorizeModule($lesson->module);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'order_index' => ['required', 'integer', 'min:1'],
        ]);

        $material->update($data);

        return back()->with('success', 'Маълумоти мавод навсозӣ шуд!');
    }

    /**
     * Нест кардани маводи мушаххас аз дарс.
     */
    public function destroyMaterial(LessonMaterial $material): RedirectResponse
    {
        $lesson = $material->lesson;
        $this->authorizeModule($lesson->module);

        $path = $material->file_path;
        $material->delete();

        if ($path && !str_starts_with($path, 'http')) {
            Storage::disk('local')->delete($path);
            Storage::disk('public')->delete($path);
        }

        return back()->with('success', 'Маводи таълимӣ нест карда шуд.');
    }

    /**
     * Коркарди боргузории файлҳои мавод ба диски хусусӣ.
     */
    private function handleUploadedMaterials(Request $request, Lesson $lesson): void
    {
        if (!$request->hasFile('materials')) {
            return;
        }

        $files = $request->file('materials');
        $titles = $request->input('material_titles', []);
        $descriptions = $request->input('material_descriptions', []);
        $currentIndex = (int) ($lesson->materials()->max('order_index') ?? 0);

        foreach ($files as $i => $file) {
            if (!$file->isValid()) {
                continue;
            }

            $ext = strtolower($file->getClientOriginalExtension());
            $mime = $file->getMimeType();
            $size = $file->getSize();
            $path = $file->store('materials/' . $lesson->id, 'local');

            $fileType = $this->detectFileType($ext);
            $title = !empty($titles[$i]) ? trim((string) $titles[$i]) : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $description = !empty($descriptions[$i]) ? trim((string) $descriptions[$i]) : null;

            LessonMaterial::create([
                'lesson_id'   => $lesson->id,
                'title'       => $title,
                'description' => $description,
                'file_path'   => $path,
                'file_type'   => $fileType,
                'mime_type'   => $mime,
                'file_size'   => $size,
                'order_index' => ++$currentIndex,
            ]);
        }
    }

    /**
     * Коркарди пайвандҳои беруна ва URL-ҳои видео (YouTube / Vimeo / Мақолаҳо).
     */
    private function handleExternalLinks(Request $request, Lesson $lesson): void
    {
        $urls = $request->input('link_urls', []);
        if (!is_array($urls) || empty($urls)) {
            return;
        }

        $titles = $request->input('link_titles', []);
        $types = $request->input('link_types', []);
        $descriptions = $request->input('link_descriptions', []);
        $currentIndex = (int) ($lesson->materials()->max('order_index') ?? 0);

        foreach ($urls as $i => $url) {
            $url = trim((string) $url);
            if ($url === '') {
                continue;
            }

            $type = ($types[$i] ?? 'link') === 'video' ? 'video' : 'link';
            $title = !empty($titles[$i]) ? trim((string) $titles[$i]) : $url;
            $description = !empty($descriptions[$i]) ? trim((string) $descriptions[$i]) : null;

            LessonMaterial::create([
                'lesson_id'   => $lesson->id,
                'title'       => $title,
                'description' => $description,
                'file_path'   => $url,
                'file_type'   => $type,
                'mime_type'   => null,
                'file_size'   => null,
                'order_index' => ++$currentIndex,
            ]);
        }
    }

    private function detectFileType(string $extension): string
    {
        return match ($extension) {
            'mp4', 'webm', 'mov', 'avi' => 'video',
            'pdf' => 'pdf',
            'doc', 'docx', 'txt', 'rtf' => 'document',
            'ppt', 'pptx' => 'presentation',
            'jpg', 'jpeg', 'png', 'webp', 'gif', 'svg' => 'image',
            default => 'document',
        };
    }

    private function authorizeModule(Module $module): void
    {
        if ($module->course->author_id !== auth()->id()) {
            abort(403);
        }
    }
}
