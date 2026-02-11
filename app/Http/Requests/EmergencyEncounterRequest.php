<?php

namespace App\Http\Requests;

use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmergencyEncounterRequest extends FormRequest
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
        return [
            'patient_name'   => ['required', 'string', 'max:255'],
            'id_number'      => ['nullable', 'string', 'max:50'],
            'contact_number' => ['required', 'string', new ValidPhoneNumber],
            'date'           => ['required', 'date'],
            'time'           => ['required', 'date_format:H:i'],
            'details'        => ['required', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'patient_name.required'   => 'Patient name is required.',
            'patient_name.max'        => 'Patient name must not exceed 255 characters.',
            'id_number.max'           => 'ID number must not exceed 50 characters.',
            'contact_number.required' => 'Contact number is required.',
            'date.required'           => 'Date is required.',
            'date.date'               => 'Please provide a valid date.',
            'time.required'           => 'Time is required.',
            'time.date_format'        => 'Please provide a valid time in HH:MM format.',
            'details.required'        => 'Emergency details are required.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
