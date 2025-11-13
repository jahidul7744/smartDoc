<?php

namespace App\Http\Requests\Admin\Notification;

use Illuminate\Foundation\Http\FormRequest;

class NotificationBulkActionRequest extends FormRequest
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
            'notification_ids' => ['required', 'array', 'min:1'],
            'notification_ids.*' => ['uuid', 'exists:notifications,id'],
        ];
    }
}

