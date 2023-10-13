<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantRequest extends FormRequest
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
        //dd($this->applicant);
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:applicants,email,' . ($this->applicant ? $this->applicant : 'NULL'),
            'skills' => 'required',
            'gender' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
    public function messages()
    {
        return [
            
            'name.required' => 'Name is required!',
            'email.required' => 'Email is required!',
            'image.required' => 'Image is required!',
            'gender.required' => 'Gender is required!',
            'skills.required' => 'Please Select atleast one skill!',
          
        ];
    }
}
