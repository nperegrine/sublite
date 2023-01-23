<?php

namespace App\Repository\Eloquent\Services;

use App\Models\Field;
use Illuminate\Support\Collection;

class SubscriberService
{
    /**
     * Sanitizes the fields and prepares them for syncing
     */
    public function sanitizeFieldsForSyncing(Collection $fieldsFromRequestParams): Collection
    {
        $fieldsForAttaching = collect();

        $fields = Field::whereIn('id', $fieldsFromRequestParams->pluck('id'))->get();

        foreach ($fields as $field) {
            $submittedFieldValue = $fieldsFromRequestParams->filter(
                fn($fieldParam) => $fieldParam['id'] === $field->id
            )->first()['value'];

            # handle boolean fields
            if ($field->type === Field::TYPE_BOOLEAN) {
                $submittedFieldValue = $field->required ? 1 : 0;
            }

            // handle non-required fields
            if (!$field->required && $submittedFieldValue === null) {
                $submittedFieldValue = '';
            }

            $fieldsForAttaching->put($field->id, ['value' => $submittedFieldValue]);
        }

        return $fieldsForAttaching;
    }

}