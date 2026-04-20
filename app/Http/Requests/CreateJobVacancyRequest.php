<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobVacancyRequest extends FormRequest
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
            'description' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required',
            'salary' => 'required|numeric',
            'company' => 'required',
            'job_category' => 'required',
        ];
    }
    public function messages() {
        return [
            'title.required' => 'The title is required',
            'title.string' => 'The title must be a string',
            'title.max' => 'The title must be less than 255 characters',
            'description.required' => 'The description is required',
            'description.string' => 'The description must be a string',
            'description.max' => 'The description must be less than 255 characters',
            'location.required' => 'The location is required',
            'location.string' => 'The location must be a string',
            'location.max' => 'The location must be less than 255 characters',
            'type.required' => 'The type is required',
            'salary.required' => 'The salary is required',
            'salary.numeric' => 'The salary must be a number',
            'company.required' => 'The company is required',
            'job_category.required' => 'The job_category is required',
        ];
    }
}
