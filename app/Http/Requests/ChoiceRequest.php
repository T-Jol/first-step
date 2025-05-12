<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => 'required|string|max:255',
            'chapter_id' => 'required|exists:chapters,id',
            'next_chapter_id' => 'required|exists:chapters,id'
        ];
    }

    public function messages(): array
    {
        return [
            'text.required' => 'Le texte est obligatoire',
            'text.max' => 'Le texte ne peut pas dépasser 255 caractères',
            'chapter_id.required' => 'Le chapitre est obligatoire',
            'chapter_id.exists' => 'Le chapitre sélectionné n\'existe pas',
            'next_chapter_id.required' => 'Le chapitre suivant est obligatoire',
            'next_chapter_id.exists' => 'Le chapitre suivant sélectionné n\'existe pas'
        ];
    }
} 