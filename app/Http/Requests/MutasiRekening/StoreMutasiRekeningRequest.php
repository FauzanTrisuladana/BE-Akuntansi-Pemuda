<?php

namespace App\Http\Requests\MutasiRekening;

use App\Models\Akun;
use App\Models\RiilHistory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreMutasiRekeningRequest extends FormRequest
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
            'date' => [
                'required',
                'date',
            ],
            'kas' => [
                'required',
                'string',
                'in:17 an,kas pemuda',
            ],
            'akun_debit_id' => [
                'required',
                'integer',
                'exists:akun,id',
            ],
            'akun_kredit_id' => [
                'required',
                'integer',
                'exists:akun,id',
            ],
            'jumlah' => [
                'required',
                'integer',
                'min:1',
            ],
            'keterangan' => [
                'nullable',
                'string',
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
        $historyRiil = RiilHistory::where('verified', true)
            ->where(function ($q) {
                $q->where('date', '>', $this->input('date'))
                    ->orWhere(function ($q2) {
                        $q2->where('date', $this->input('date'))
                            ->where('akun_id', $this->input('akun_debit_id'));
                    })
                    ->orWhere(function ($q2) {
                        $q2->where('date', $this->input('date'))
                            ->where('akun_id', $this->input('akun_kredit_id'));
                    });
            })->exists();
        $validator->after(function ($validator) use ($historyRiil) {
            if ($historyRiil) {
                $validator->errors()->add('date', 'Tidak dapat membuat mutasi rekening pada tanggal ini karena sudah ada riil history yang terverifikasi setelahnya');
            }

            if ($this->akun_debit_id == $this->akun_kredit_id) {
                $validator->errors()->add('akun_kredit_id', 'Akun kredit tidak boleh sama dengan akun debit');
            }

            if ($this->akun_debit_id) {
                $akunDebit = Akun::find($this->akun_debit_id);
                if ($akunDebit instanceof Akun && $akunDebit->kas !== $this->kas) {
                    $validator->errors()->add('akun_debit_id', 'Akun debit tidak sesuai dengan kas yang dipilih');
                }
            }

            if ($this->akun_kredit_id) {
                $akunKredit = Akun::find($this->akun_kredit_id);
                if ($akunKredit instanceof Akun && $akunKredit->kas !== $this->kas) {
                    $validator->errors()->add('akun_kredit_id', 'Akun kredit tidak sesuai dengan kas yang dipilih');
                }
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
