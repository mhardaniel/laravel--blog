<?php

namespace App\Http\Requests;

class NewArticleRequest extends BaseArticleRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge_recursive(parent::rules(), [
            'title' => ['required'],
            'slug' => ['required'],
            'description' => ['required'],
            'body' => ['required'],
            'tagList' => 'sometimes|array',
            'tagList.*' => 'required|string|max:255',
        ]);
    }
}
