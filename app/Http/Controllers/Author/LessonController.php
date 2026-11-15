<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonMaterial;
use App\Models\Module;
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
            'title'            => ['required', 'string', 'max:255'],
            'content_type'     => ['required', 'in:text,video,file'],
            'content_text'     => ['nullable', 'string'],
            'video_url'        => ['nullable', 'url', 'max:500'],
            'file'             => ['nullable', 'file', 'max:102400', 'mimes:mp4,webm,doc,docx,pdf,jpg,jpeg,png,webp,ppt,pptx'],
            'order_index'      => ['required', 'integer', 'min:1'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'materials'        => ['nullable', 'array'],
            'materials.*'      => ['file', 'max:102400', 'mimes:mp4,webm,doc,docx,pdf,jpg,jpeg,png,webp,ppt,pptx'],
            'material_titles'  => ['nullable', 'array'],
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('lessons', 'public');
            $data['file_path'] = $path;
        }
        unset($data['file']);
        if (isset($data['content_text'])) {
            $data['body_text'] = $data['content_text'];
            unset($data['content_text']);
        }
        unset($data['duration_minutes'], $data['materials'], $data['material_titles']);
        $data['module_id'] = $module->id;

        $lesson = Lesson::create($data);

        // Нигоҳдории маводҳои иловагӣ
        $this->handleUploadedMaterials($request, $lesson);

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

    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $module = $lesson->module;
        $this->authorizeModule($module);

        $data = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'content_type'     => ['required', 'in:text,video,file'],
            'content_text'     => ['nullable', 'string'],
            'video_url'        => ['nullable', 'url', 'max:500'],
            'file'             => ['nullable', 'file', 'max:102400', 'mimes:mp4,webm,doc,docx,pdf,jpg,jpeg,png,webp,ppt,pptx'],
            'order_index'      => ['required', 'integer', 'min:1'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'materials'        => ['nullable', 'array'],
            'materials.*'      => ['file', 'max:102400', 'mimes:mp4,webm,doc,docx,pdf,jpg,jpeg,png,webp,ppt,pptx'],
            'material_titles'  => ['nullable', 'array'],
        ]);

        if ($request->hasFile('file')) {
            if ($lesson->file_path) {
                Storage::disk('public')->delete($lesson->file_path);
            }
            $data['file_path'] = $request->file('file')->store('lessons', 'public');
        }
        unset($data['file']);
        if (isset($data['content_text'])) {
            $data['body_text'] = $data['content_text'];
            unset($data['content_text']);
        }
        unset($data['duration_minutes'], $data['materials'], $data['material_titles']);

        $lesson->update($data);

        // Нигоҳдории маводҳои нав
        $this->handleUploadedMaterials($request, $lesson);

        return redirect()
            ->route('author.courses.show', $module->course)
            ->with('success', 'Дарс бо муваффақият навсозӣ шуд!');
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        $module = $lesson->module;
        $this->authorizeModule($module);

        // Нест кардани файлҳои маводҳо
        foreach ($lesson->materials as $material) {
            Storage::disk('public')->delete($material->file_path);
        }

        if ($lesson->file_path) {
            Storage::disk('public')->delete($lesson->file_path);
        }
        $lesson->delete();

        return redirect()
            ->route('author.courses.show', $module->course)
            ->with('success', 'Дарс нест карда шуд.');
    }

    /**
     * Нест кардани маводи мушаххас аз дарс.
     */
    public function destroyMaterial(LessonMaterial $material): RedirectResponse
    {
        $lesson = $material->lesson;
        $this->authorizeModule($lesson->module);

        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return back()->with('success', 'Маводи таълимӣ нест карда шуд.');
    }

    /**
     * Коркарди боргузории маводҳои гуногун.
     */
    private function handleUploadedMaterials(Request $request, Lesson $lesson): void
    {
        if (!$request->hasFile('materials')) {
            return;
        }

        $files = $request->file('materials');
        $titles = $request->input('material_titles', []);
        $currentIndex = $lesson->materials()->count();

        foreach ($files as $i => $file) {
            if (!$file->isValid()) {
                continue;
            }

            $ext = strtolower($file->getClientOriginalExtension());
            $mime = $file->getMimeType();
            $size = $file->getSize();
            $path = $file->store('materials/' . $lesson->id, 'public');

            $fileType = $this->detectFileType($ext);

            $title = !empty($titles[$i]) ? $titles[$i] : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            LessonMaterial::create([
                'lesson_id'   => $lesson->id,
                'title'       => $title,
                'description' => null,
                'file_path'   => $path,
                'file_type'   => $fileType,
                'mime_type'   => $mime,
                'file_size'   => $size,
                'order_index' => ++$currentIndex,
            ]);
        }
    }

    /**
     * Муайян кардани намуди файл.
     */
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
