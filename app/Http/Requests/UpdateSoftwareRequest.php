<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSoftwareRequest extends FormRequest
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
            'nome' => 'required|string',
            'software_imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Validação dos campos de requisitos
            'cpu_min' => 'required|string',
            'gpu_min' => 'required|string',
            'ram_min' => 'required|string',
            'placa_mae_min' => 'nullable|string',
            'ssd_min' => 'nullable|string',
            'cooler_min' => 'nullable|string',
            'fonte_min' => 'nullable|string',

            'cpu_med' => 'required|string',
            'gpu_med' => 'required|string',
            'ram_med' => 'required|string',
            'placa_mae_med' => 'nullable|string',
            'ssd_med' => 'nullable|string',
            'cooler_med' => 'nullable|string',
            'fonte_med' => 'nullable|string',

            'cpu_rec' => 'required|string',
            'gpu_rec' => 'required|string',
            'ram_rec' => 'required|string',
            'placa_mae_rec' => 'nullable|string',
            'ssd_rec' => 'nullable|string',
            'cooler_rec' => 'nullable|string',
            'fonte_rec' => 'nullable|string',
        ];
    }
}
