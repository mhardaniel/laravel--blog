<?php

namespace App\Http\Requests;

class UpdateArticleRequest extends BaseArticleRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return array_merge_recursive(parent::rules(), [
            'title' => ['required_without_all:description,body'],
            'slug' => ['required_with:title'],
            'description' => ['required_without_all:title,body'],
            'body' => ['required_without_all:title,description'],
        ]);
    }
}
