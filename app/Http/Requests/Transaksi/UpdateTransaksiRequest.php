<?php

namespace App\Http\Requests\Transaksi;

use App\Models\RiilHistory;
use App\Models\Transaksi;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransaksiRequest extends FormRequest
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
        $this->merge([
            'penginput_id' => $this->user()->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'deskripsi' => [
                'nullable',
                'string',
                'max:255',
            ],
            'penanggung_jawab_id' => [
                'nullable',
                'integer',
                'exists:penanggung_jawab,id',
            ],
            'jumlah' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'bukti' => [
                'sometimes',
                'nullable',
                'image',
                'max:5120', // 5MB
            ],
            'penginput_id' => [
                'required',
                'integer',
                'exists:users,id',
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
        $transaksi = Transaksi::findOrFail($this->route('transaksi'));
        $riilHistory = RiilHistory::where('verified', true)
            ->where('akun_id', $transaksi->akun_id)
            ->where('date', '>=', $transaksi->date)
            ->exists();
        $validator->after(function ($validator) use ($transaksi, $riilHistory) {
            if ($riilHistory && $transaksi->jumlah != $this->input('jumlah')) {
                $validator->errors()->add('jumlah', 'Transaksi ini tidak dapat diubah jumlahnya karena sudah ada history riil yang diverifikasi setelahnya.');
            }
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
