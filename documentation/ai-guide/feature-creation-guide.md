# Panduan Pembuatan Fitur Baru

Panduan ini ditulis untuk AI yang akan membuat fitur baru dalam proyek Laravel Akuntansi Pemuda.

## Daftar Isi
1. [Struktur Folder](#struktur-folder)
2. [Membuat Controller](#membuat-controller)
3. [Membuat Request](#membuat-request)
4. [Membuat Resource](#membuat-resource)
5. [Menambahkan Route](#menambahkan-route)
6. [Validasi Kode](#validasi-kode)

---

## Struktur Folder

```
app/
├── Http/
│   ├── Controllers/           # Controller utama
│   ├── Requests/              # Form Request untuk validasi
│   │   └── NamaFitur/         # Folder per fitur (contoh: User, PJ, Profile)
│   └── Resources/             # Resource untuk response API
```

---

## Membuat Controller

### Perintah Artisan
Gunakan perintah artisan untuk membuat controller:

```bash
php artisan make:controller NamaController extends Controller
```

### Struktur Controller

Controller harus extends `Controller` dan mengikuti pola berikut:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\NamaFitur\IndexNamaRequest;
use App\Http\Requests\NamaFitur\StoreNamaRequest;
use App\Http\Requests\NamaFitur\UpdateNamaRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiResourceCollection;
use App\Http\Resources\NamaResource;
use App\Models\NamaModel;

class NamaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Get /api/nama-endpoint
     */
    public function index(IndexNamaRequest $request): ApiResourceCollection
    {
        $validated = $request->validated();

        $data = NamaModel::filter(
            search: $validated['search'] ?? null,
        )
            ->orderBy('nama')
            ->paginate($validated['per_page']);

        return NamaResource::collection($data)
            ->message('Data nama berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     * Post /api/nama-endpoint
     */
    public function store(StoreNamaRequest $request): NamaResource
    {
        $validated = $request->validated();

        $data = NamaModel::create([
            'nama' => $validated['nama'],
            // field lainnya
        ]);

        return (new NamaResource($data))
            ->message('Nama berhasil dibuat');
    }

    /**
     * Update the specified resource in storage.
     * Put /api/nama-endpoint/{id}
     */
    public function update(UpdateNamaRequest $request, string $id): NamaResource
    {
        $validated = $request->validated();

        $data = NamaModel::findOrFail($id);

        $data->update($validated);

        return (new NamaResource($data))
            ->message('Nama berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     * Delete /api/nama-endpoint/{id}
     */
    public function destroy(string $id): ApiResource
    {
        $data = NamaModel::findOrFail($id);

        $data->delete();

        return (new ApiResource(null))
            ->message('Nama berhasil dihapus');
    }
}
```

### Catatan:
- Gunakan `findOrFail()` untuk menemukan data atau mengembalikan 404
- Untuk soft delete, update status menjadi 'Tidak Aktif' sebelum delete
- Gunakan method `filter()` pada model untuk pencarian dan filter
- Kembalikan response dengan method `message()` untuk konsistensi format

### Menambahkan Scope Filter di Model

Jika fitur memerlukan pencarian/filter, tambahkan scope di model:

```php
// Di dalam model
public function scopeFilter($query, ?string $search = null, ?string $status = null)
{
    if ($search) {
        $query->where('nama', 'like', "%$search%");
    }

    if ($status) {
        $query->where('status', $status);
    }

    return $query;
}
```

Pastikan model menggunakan `SoftDeletes` jika diperlukan:
```php
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class NamaModel extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['nama', 'field_lainnya'];
    protected $casts = ['field_angka' => 'integer'];
}

---

## Membuat Request

### Perintah Artisan
Gunakan perintah artisan untuk membuat Form Request:

```bash
php artisan make:request NamaFitur/IndexNamaRequest
php artisan make:request NamaFitur/StoreNamaRequest
php artisan make:request NamaFitur/UpdateNamaRequest
```

### Struktur Request

Semua Request harus extends `FormRequest` dan mengikuti pola berikut:

```php
<?php

namespace App\Http\Requests\NamaFitur;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class IndexNamaRequest extends FormRequest
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
        // Merge data default sebelum validasi
        // Contoh: $this->merge(['status' => 'Aktif']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
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
            'search' => [
                'sometimes',
                'string',
                'max:255',
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
            // Custom validation logic
            // Contoh:
            // if ($this->something_invalid) {
            //     $validator->errors()->add('field', 'Custom error');
            // }
        });
    }
}
```

### Struktur Store Request (dengan prepareForValidation)

```php
<?php

namespace App\Http\Requests\NamaFitur;

use Illuminate\Foundation\Http\FormRequest;

class StoreNamaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Set nilai default sebelum validasi
        $this->merge([
            'status' => 'Aktif',
            'activated_at' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            // field lainnya
        ];
    }
}
```

### Catatan:
- Gunakan `sometimes` untuk field yang opsional
- Gunakan `prepareForValidation()` untuk set nilai default
- Gunakan `withValidator()` untuk custom validation logic
- Pastikan semua field yang diperlukan memiliki rule `required`

---

## Membuat Resource

### Perintah Artisan
Gunakan perintah artisan untuk membuat Resource:

```bash
php artisan make:resource NamaResource
```

### Struktur Resource

Resource harus extends `ApiResource` dan mengikuti pola berikut:

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class NamaResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas('id'),
            'nama' => $this->whenHas('nama'),
            'email' => $this->whenHas('email'),
            'status' => $this->whenHas('status'),
            
            // Relasi dengan eager loading
            'relasi_lain' => TransaksiResource::collection($this->whenLoaded('relasiLain')),
        ];
    }
}
```

### Catatan:
- Gunakan `$this->whenHas('field')` untuk field yang mungkin null
- Gunakan `$this->whenLoaded('relation')` untuk relasi yang di-eager-load
- Kembalikan array dengan format snake_case untuk konsistensi API

---

## Menambahkan Route

Tambahkan route di file `routes/api.php` dengan pola berikut:

```php
/**
 * Routes untuk fitur nama
 * Get /api/nama-endpoint -> get list nama
 * Post /api/nama-endpoint -> create nama baru
 * Put /api/nama-endpoint/{id} -> update nama dengan id tertentu
 * Delete /api/nama-endpoint/{id} -> delete nama dengan id tertentu
 */
Route::apiResource('nama-endpoint', NamaController::class)
    ->except(['show']); // Hapus jika show diperlukan
```

### Route dengan Middleware Role

```php
/**
 * Routes untuk role bendahara
 * Hanya bisa diakses oleh user dengan role bendahara
 */
Route::middleware('role:bendahara')->group(function () {
    Route::apiResource('nama-endpoint', NamaController::class);
});
```

### Route untuk Profile (tanpa middleware role)

```php
/**
 * Profile routes
 * Get /api/profile/me -> get profile user yang sedang login
 * Put /api/profile/update -> update profile user yang sedang login
 */
Route::prefix('profile')->controller(ProfileController::class)->group(function () {
    Route::get('/me', 'me');
    Route::put('/update', 'update');
});
```

---

## Validasi Kode

### Jalankan PHP Code Lint

Setelah selesai membuat fitur, jalankan perintah untuk validasi kode:

```bash
php artisan code:lint
```

### Perbaiki Error

Jika terdapat error, perbaiki dengan:

1. **Syntax Error**: Periksa kurung, koma, dan tanda baca
2. **Import Error**: Pastikan semua class yang digunakan sudah di-import
3. **Type Error**: Pastikan return type sesuai dengan Resource yang digunakan
4. **Validation Error**: Periksa kembali rule validasi di Request

### Contoh Output Error dan Perbaikan

**Error**: `Class "App\Http\Requests\NamaFitur\StoreNamaRequest" not found`

**Perbaikan**: Pastikan file Request sudah dibuat dengan benar dan namespace sesuai

**Error**: `Return type must be ApiResourceCollection|JsonResource|UserResource`

**Perbaikan**: Pastikan return type di controller sesuai dengan Resource yang digunakan

---

## Contoh Implementasi Lengkap

### Membuat fitur "Kategori"

1. **Buat Model dan Migration** (jika belum ada):
```bash
php artisan make:model Kategori -m
```

2. **Buat Controller**:
```bash
php artisan make:controller KategoriController extends Controller
```

3. **Buat Request**:
```bash
php artisan make:request Kategori/IndexKategoriRequest
php artisan make:request Kategori/StoreKategoriRequest
php artisan make:request Kategori/UpdateKategoriRequest
```

4. **Buat Resource**:
```bash
php artisan make:resource KategoriResource
```

5. **Tambahkan Route** di `routes/api.php`:
```php
Route::apiResource('kategori', KategoriController::class);
```

6. **Jalankan Lint**:
```bash
php artisan code:lint
```

7. **Perbaiki semua error** yang muncul

---

## Checklist Sebelum Selesai

- [ ] Controller dibuat dengan artisan
- [ ] Request dibuat dengan artisan (Index, Store, Update)
- [ ] Resource dibuat dengan artisan
- [ ] Route sudah ditambahkan di `routes/api.php`
- [ ] Semua import sudah benar
- [ ] Method `filter()` sudah ada di Model (jika diperlukan)
- [ ] `php artisan code:lint` tidak ada error
- [ ] Format response sesuai dengan pola yang ada
