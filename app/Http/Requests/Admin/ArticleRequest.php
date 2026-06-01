<?php

namespace App\Http\Requests\Admin;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $title = trim((string) $this->input('title'));
        $slug = trim((string) $this->input('slug'));

        $this->merge([
            'title' => $title,
            'slug' => $slug !== '' ? Str::slug($slug) : ($title !== '' ? Str::slug($title) : null),
            'category' => $this->filled('category') ? trim((string) $this->input('category')) : 'Artikel',
            'image_url' => $this->filled('image_url') ? trim((string) $this->input('image_url')) : null,
            'excerpt' => $this->filled('excerpt') ? trim((string) $this->input('excerpt')) : null,
            'content' => trim((string) $this->input('content')),
        ]);
    }

    public function rules(): array
    {
        $article = $this->route('article');
        $ignoreId = $article instanceof Article ? $article->getKey() : null;

        return [
            'title' => ['required', 'string', 'min:3', 'max:180'],
            'slug' => [
                'required',
                'string',
                'max:200',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('articles', 'slug')->ignore($ignoreId),
            ],
            'category' => ['required', 'string', 'min:2', 'max:80'],
            'image_url' => ['nullable', 'url', 'max:1000'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:20'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
        ];
    }
}
