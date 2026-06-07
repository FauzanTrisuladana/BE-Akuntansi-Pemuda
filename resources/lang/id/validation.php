<?php

return [
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'integer' => ':attribute harus berupa angka.',
    'email' => 'Format :attribute tidak valid.',
    'unique' => ':attribute sudah terdaftar.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'in' => ':attribute yang dipilih tidak valid.',
    'date' => ':attribute tidak valid.',
    'date_format' => ':attribute tidak sesuai format :format.',
    'after_or_equal' => ':attribute harus sama atau setelah :date.',

    'numeric' => ':attribute harus berupa angka.',

    'between' => [
        'string' => ':attribute harus antara :min dan :max karakter.',
        'numeric' => ':attribute harus antara :min dan :max.',
        'file' => ':attribute harus berukuran antara :min dan :max kilobytes.',
        'array' => ':attribute harus berisi antara :min dan :max item.',
    ],

    'decimal' => ':attribute harus berupa angka dengan maksimal :decimal tempat desimal.',

    'present' => ':attribute harus ada.',

    'confirmed' => 'Konfirmasi :attribute tidak cocok.',

    'same' => ':attribute dan :other harus sama.',

    'regex' => 'Format :attribute tidak valid.',

    'url' => 'Format :attribute tidak valid.',

    'image' => ':attribute harus berupa gambar.',

    'mimes' => ':attribute harus berupa file dengan tipe: :values.',

    'min' => [
        'string' => ':attribute minimal :min karakter.',
        'numeric' => ':attribute minimal :min.',
        'file' => ':attribute minimal :min kilobytes.',
        'array' => ':attribute minimal :min item.',
    ],

    'max' => [
        'string' => ':attribute maksimal :max karakter.',
        'numeric' => ':attribute maksimal :max.',
        'file' => ':attribute maksimal :max kilobytes.',
        'array' => ':attribute maksimal :max item.',
    ],

    'attributes' => [
        'user_id' => 'user',
        'role_id' => 'role',
        'nama' => 'nama',
        'ktp' => 'no. KTP',
        'email' => 'email',
        'telp' => 'no. telepon',
        'gender' => 'gender',
        'photo_profile' => 'photo profile',
        'status' => 'status',
        'tanggal_masuk' => 'tanggal masuk',
        'tanggal_keluar' => 'tanggal keluar',
        'name' => 'nama',
        'search' => 'pencarian',
        'permissions' => 'daftar permission',
        'class' => 'class permission',
        'level' => 'level permission',
        'password' => 'password',
        'id_token' => 'ID token',
        'device_name' => 'nama perangkat',
        'koperasi_id' => 'koperasi',
        'nama_posisi' => 'nama posisi',
        'jenis_posisi' => 'jenis posisi',
        'multiple' => 'multiple',
        'tipe' => 'tipe',
        'suku_bunga' => 'suku bunga',
        'keterangan' => 'keterangan',
        'anggota_id' => 'anggota',
        'anggota' => 'anggota',
        'jabatan_id' => 'jabatan',
        'mulai' => 'tahun mulai',
        'selesai' => 'tahun selesai',
    ],

    'custom' => [
        'mulai' => [
            'date_format' => ':attribute harus berupa tahun.',
        ],
        'selesai' => [
            'date_format' => ':attribute harus berupa tahun.',
        ],
    ],
];
