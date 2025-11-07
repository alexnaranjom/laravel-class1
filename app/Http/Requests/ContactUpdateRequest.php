<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // apiResource uses implicit binding -> route('contact') is a Contact model
        $contact = $this->route('contact'); 
        $id = $contact ? $contact->id : null;
        
        return [
            'name'  => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', "unique:contacts,email,{$id}"],
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }
}
