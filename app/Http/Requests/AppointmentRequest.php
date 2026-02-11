<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
        if (request()->routeIs('appointments.store')) {
            return [
                'user_id'            => 'required|exists:users,user_id',
                'schedule_id'        => 'required|exists:schedules,schedule_id',
                'appointment_date'   => 'required|date|date_format:Y-m-d',
                'appointment_time'   => 'required|date_format:H:i',
                'reason_category_id' => 'nullable|exists:reason_categories,reason_category_id',
            ];
        } elseif (request()->routeIs('appointments.update')) {
            return [
                'user_id'            => 'sometimes|exists:users,user_id',
                'schedule_id'        => 'sometimes|exists:schedules,schedule_id',
                'appointment_date'   => 'sometimes|date|date_format:Y-m-d',
                'appointment_time'   => 'sometimes|date_format:H:i',
                'reason_category_id' => 'nullable|exists:reason_categories,reason_category_id',
                'status'             => 'sometimes|in:pending,confirmed,cancelled,completed',
            ];
        }

        return [];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required'          => 'User ID is required.',
            'user_id.exists'            => 'The specified user does not exist.',
            'schedule_id.required'      => 'Schedule ID is required.',
            'schedule_id.exists'        => 'The specified schedule does not exist.',
            'appointment_date.required' => 'Appointment date is required.',
            'appointment_date.date'     => 'Appointment date must be a valid date.',
            'appointment_time.required' => 'Appointment time is required.',
            'appointment_time.date_format' => 'Appointment time must be in H:i format (e.g., 09:00).',
        ];
    }
}
