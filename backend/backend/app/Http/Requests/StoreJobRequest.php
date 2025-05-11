<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'qualifications' => 'nullable|string',
            'salary_from' => 'nullable|numeric|min:0',
            'salary_to' => 'nullable|numeric|gte:salary_from',
            'location' => 'required|string|max:255',
            'job_type' => 'required|in:remote,on-site,hybrid',
            'deadline' => 'required|date|after:today',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg||max:2048',
        ];
    }
}
