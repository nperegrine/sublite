<?php

namespace App\Http\Requests\Subscribers;

use App\Models\Field;
use App\Models\Subscriber;
use App\Rules\ValidEmailHost;
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
            'name' => 'required|min:5',
            'email' => [
                'required', 
                'email:rfc,dns', 
                'max:250',
                new ValidEmailHost(), 
                Rule::unique('subscribers', 'email')
                    ->ignore($this->subscriber)
                    ->withoutTrashed()
            ],
            'state' => 'nullable|in:' . implode(',', Subscriber::STATES),
            'fields.*.title' => 'nullable|max:250|exists:fields,title',
            'fields.*.value' => [
                function ($attribute, $value, $fail) {
                    $this->validateCustomField($attribute, $value, $fail);
                }
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('Name'),
            'email' => __('Email'),
            'state' => __('State'),
            'fields.*.title' => __('Field Title'),
            'fields.*.type' => __('Field Type'),
            'fields.*.value' => __('Field Value'),
        ];
    }

    private function validateCustomField(string $attribute, mixed $value, callable $fail): void
    {
        $attributeIndex = explode('.', $attribute)[1];
        $fieldTitle = $this->input("fields.$attributeIndex.title");
        $field = Field::where('title', $fieldTitle)->first();

        if (!$field) {
            return;
        }

        $validationError = $field->validate($value);

        if ($validationError) {
            $fail($validationError);
        }
    }
}