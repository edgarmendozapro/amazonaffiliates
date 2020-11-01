<?php

namespace EdgarMendozaTech\AmazonAffiliates\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AmazonLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:1|max:255',
            'media_resources' => 'nullable|array',
            'media_resources.*' => 'nullable|exists:media_resources,id',
        ];
    }
}
