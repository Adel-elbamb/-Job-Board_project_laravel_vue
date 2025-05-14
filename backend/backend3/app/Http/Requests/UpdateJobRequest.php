<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
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
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'responsibilities' => 'nullable|string',
            'qualifications' => 'nullable|string',
            'salary_from' => 'nullable|numeric|min:0',
            'salary_to' => 'nullable|numeric|gte:salary_from',
            'location' => 'sometimes|required|string|max:255',
            'job_type' => 'sometimes|required|in:remote,on-site,hybrid',
            'deadline' => 'sometimes|required|date|after:today',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
