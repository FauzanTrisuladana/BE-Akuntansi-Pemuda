<?php

namespace App\Http\Requests\Transaksi;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class IndexTransaksiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * This method is called before validation is applied.
     */
    protected function prepareForValidation(): void
    {
        //
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],
            'page' => [
                'required',
                'integer',
                'min:1',
            ],
            'per_page' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],
            'tanggal_mulai' => [
                'nullable',
                'date',
                'before_or_equal:tanggal_selesai',
            ],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'jenis_transaksi' => [
                'required',
                'array',
            ],
            'jenis_transaksi.*' => [
                'required',
                'string',
                'in:pemasukan,pengeluaran',
            ],
            'kas' => [
                'required',
                'array',
            ],
            'kas.*' => [
                'required',
                'string',
                'in:17 an,kas pemuda',
            ],
            'akun' => [
                'nullable',
                'integer',
                'exists:akun,id',
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * Used to add additional validation after the main rules.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // ex:
            // if ($this->something_invalid) {
            //     $validator->errors()->add('field', 'Custom error');
            // }
        });
    }

    /**
     * Handle a passed validation attempt.
     *
     * This method is called after validation is successful.
     */
    protected function passedValidation(): void
    {
        //
    }

    /**
     * Handle failed authorization.
     *
     * @return void
     */
    protected function failedAuthorization()
    {
        abort(403, 'Tidak memiliki izin untuk melakukan aksi ini');
    }
}
