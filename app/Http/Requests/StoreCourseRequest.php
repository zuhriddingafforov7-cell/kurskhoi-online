<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAuthor() || auth()->user()->isAdmin());
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Номи курс ҳатмист.',
            'description.required' => 'Тавсифи курс ҳатмист.',
            'category_id.required' => 'Интихоби категория ҳатмист.',
            'category_id.exists'   => 'Категорияи интихобшуда вуҷуд надорад.',
        ];
    }
}
