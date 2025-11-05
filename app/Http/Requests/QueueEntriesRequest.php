<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QueueEntriesRequest extends FormRequest
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
      if( request()->routeIs('queues.store')  ) {
          return [
              'user_id'       => 'required|exists:users,user_id',
              'reason'        => 'required|string|max:255',
          ];
      }
      else if( request()->routeIs('queues.update.status') ) {
          return [
              'queue_status'  => 'sometimes|required|in:waiting,called,completed,cancelled',
          ];
      } 
      else if( request()->routeIs('queues.update.reason') ) {
          return [
              'reason'        => 'sometimes|required|string|max:255',
          ];
      }
      else if( request()->routeIs('queues.admin.update'))  {
          return [
              'queue_status'        => 'sometimes|in:waiting,called,completed,cancelled',
              'estimated_wait_time' => 'sometimes|nullable|string|max:255',
          ];
      }

        return [];
    }
}
