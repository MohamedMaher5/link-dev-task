<?php

namespace App\Http\Requests;

use App\Models\ProviderAvailability;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AvailabilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('provider');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function (Validator $validator) {
            $exists = ProviderAvailability::where('provider_id', $this->user()->id)
                ->where('day_of_week', $this->day_of_week)
                ->where(function ($query) {
                    $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                        ->orWhereBetween('end_time', [$this->start_time, $this->end_time])
                        ->orWhere(function ($q) {
                            $q->where('start_time', '<=', $this->start_time)
                                ->where('end_time', '>=', $this->end_time);
                        });
                })
                ->exists();

            if ($exists) {
                $validator->errors()->add('time', 'This time slot overlaps with an existing availability.');
            }
        });
    }
}
