<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChapterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'story_id' => 'required|exists:stories,id',
            'is_ending' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères',
            'content.required' => 'Le contenu est obligatoire',
            'story_id.required' => 'L\'histoire est obligatoire',
            'story_id.exists' => 'L\'histoire sélectionnée n\'existe pas'
        ];
    }
} 