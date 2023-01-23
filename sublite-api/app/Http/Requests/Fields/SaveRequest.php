<?php

namespace App\Http\Requests\Fields;

use App\Models\Field;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [
                'required',
                'max:250',
                Rule::unique('fields', 'title')
                    ->ignore($this->route('id'))
                    ->withoutTrashed()
            ],
            'type' => 'required|in:' . implode(',', Field::TYPES),
            'required' => 'present|bool',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => __('Title'),
            'type' => __('Type'),
            'required' => __('Required'),
        ];
    }
}