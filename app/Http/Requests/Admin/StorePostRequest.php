<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'type'   => 'required|in:article,gallery,video,audio,poll,trivia_quiz,personality_quiz,recipe,event,sorted_list,live_blog',
            'status' => 'required|in:draft,pending,published,scheduled,archived',
            'category_id'        => 'nullable|exists:categories,id',
            'is_featured'        => 'boolean',
            'is_breaking'        => 'boolean',
            'is_premium'         => 'boolean',
            'paywall_type'       => 'in:none,hard,soft,fade',
            'free_paragraphs'    => 'integer|min:1|max:20',
            'allow_comments'     => 'boolean',
            'source_name'        => 'nullable|string|max:255',
            'source_url'         => 'nullable|url|max:500',
            'published_at'       => 'nullable|date',
            'featured_image'     => 'nullable|string',
            'featured_image_caption' => 'nullable|string|max:500',
            'translations'       => 'required|array',
            'translations.dv.title' => 'required|string|max:255',
            'translations.dv.slug'  => 'nullable|string|max:255',
            'translations.en.title' => 'nullable|string|max:255',
            'tag_ids'            => 'nullable|array',
            'tag_ids.*'          => 'exists:tags,id',
            'author_ids'         => 'nullable|array',
            'author_ids.*'       => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'translations.dv.title.required' => 'A Dhivehi title is required.',
        ];
    }
}
