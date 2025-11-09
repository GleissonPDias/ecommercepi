<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {   

        $user = $this->user();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','string','lowercase','email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'last_name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'cpf' => ['nullable', 'string', 'max:14', Rule::unique(User::class)->ignore($user->id)],
            'phone_number'=>['nullable', 'string', 'max:20', Rule::unique(User::class)->ignore($user->id)],
            'birth_date'=>['nullable', 'date'],

            'photo'=>['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
