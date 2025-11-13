<?php

namespace App\Http\Requests\Admin\Notification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BroadcastNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
            'action_url' => ['nullable', 'url', 'max:255'],
            'scope' => ['required', Rule::in(['all', 'role', 'users'])],
            'role' => ['required_if:scope,role', Rule::in(['patient', 'doctor', 'diagnostic_center'])],
            'user_ids' => ['required_if:scope,users', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ];
    }
}

