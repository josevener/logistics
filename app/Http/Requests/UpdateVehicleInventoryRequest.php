<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateVehicleInventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'Admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_number' => 'required|string|max:255',
            'truck_type' => 'required|string|max:255',
            'route_from' => 'required|string|max:255',
            'route_to' => 'required|string|max:255',
            'total_capacity' => 'required|numeric|min:1',
            'available_capacity' => 'required|numeric|min:0|lte:total_capacity',
            'status' => 'required|in:ready,maintenance',
        ];
    }
}
