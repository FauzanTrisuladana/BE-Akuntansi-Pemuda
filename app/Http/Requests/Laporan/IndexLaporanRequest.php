<?php

namespace App\Http\Requests\Laporan;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class IndexLaporanRequest extends FormRequest
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
            'tanggal_mulai' => [
                'required',
                'date',
                'before_or_equal:tanggal_selesai',
            ],
            'tanggal_selesai' => [
                'required',
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
