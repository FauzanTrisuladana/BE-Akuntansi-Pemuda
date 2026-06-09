# Panduan Menulis Hoppscotch Collection

## Struktur Dasar Collection

```json
{
  "v": 12,
  "id": "unique-id-collection",
  "name": "Nama - Nama Collection",
  "folders": [],
  "requests": [...],
  "auth": {...},
  "headers": [...],
  "variables": [...]
}
```

## Penulisan Endpoint

- `<<link>>` sudah menyertakan prefix `/api` secara otomatis
- Jika endpoint adalah `/api/status`, cukup tulis `<<link>>/status`
- Jika endpoint adalah `/api/transaksi`, cukup tulis `<<link>>/transaksi`

### Subfolder Collection

Untuk collection yang berada di folder/subfolder, gunakan variable `prefix` untuk menambahkan path tambahan:

- Variable `prefix` dengan nilai `/profile` akan membuat `<<link>>` menjadi `/api/profile`
- Jika endpoint adalah `/api/profile/me`, cukup tulis `<<link>>/me` (bukan `<<link>>/profile/me`)
- Ini berguna untuk mengelompokkan endpoint yang memiliki prefix yang sama

Contoh:
```json
{
  "variables": [
    {
      "key": "prefix",
      "initialValue": "/profile",
      "currentValue": "",
      "secret": false
    }
  ]
}
```

Dengan prefix di atas, endpoint `<<link>>/me` akan menjadi `/api/profile/me`

## Penulisan Body

### Body Kosong (GET/DELETE)
```json
"body": {
  "body": null,
  "contentType": null
}
```

### Body JSON (POST/PUT)
```json
"body": {
  "body": "{\n  \"nama\": \"contoh\",\n  \"jumlah\": 10000\n}",
  "contentType": "application/json"
}
```

### Body Form Data
```json
"body": {
  "body": null,
  "contentType": "multipart/form-data"
}
```

## Contoh Collection Status

```json
{
  "v": 12,
  "id": "status-check-collection",
  "name": "Status - Status Check",
  "folders": [],
  "requests": [
    {
      "v": "17",
      "id": "status-get",
      "auth": {
        "authType": "inherit",
        "authActive": true
      },
      "body": {
        "body": null,
        "contentType": null
      },
      "name": "Get Status",
      "method": "GET",
      "params": [],
      "headers": [],
      "endpoint": "<<link>>/status",
      "responses": {},
      "testScript": "",
      "description": "Check API status",
      "preRequestScript": "",
      "requestVariables": []
    }
  ],
  "headers": [],
  "variables": [
    {
      "key": "prefix",
      "initialValue": "",
      "currentValue": "",
      "secret": false
    }
  ]
}
```

## Catatan
- Gunakan `authType: "inherit"` untuk menggunakan auth dari collection parent
- Variable `prefix` akan ditambahkan ke `<<link>>` (bukan menggantikan)
- Untuk collection utama tanpa subfolder, gunakan `prefix: ""` atau hapus variable ini
- Untuk subfolder, gunakan `prefix: "/profile"` sehingga `<<link>>/me` menjadi `/api/profile/me`

## Contoh Collection Subfolder (Profile)

Contoh collection untuk endpoint dengan prefix `/api/profile`:

```json
{
  "v": 12,
  "id": "profile-collection",
  "name": "Profile - Profile Management",
  "folders": [],
  "requests": [
    {
      "v": "17",
      "id": "profile-me",
      "auth": {
        "authType": "inherit",
        "authActive": true
      },
      "body": {
        "body": null,
        "contentType": null
      },
      "name": "Get Profile",
      "method": "GET",
      "params": [],
      "headers": [],
      "endpoint": "<<link>><<prefix>>/me",
      "responses": {},
      "testScript": "",
      "description": "Mengambil profile user yang sedang login",
      "preRequestScript": "",
      "requestVariables": []
    },
    {
      "v": "17",
      "id": "profile-update",
      "auth": {
        "authType": "inherit",
        "authActive": true
      },
      "body": {
        "body": "{\n  \"name\": \"Nama User\",\n  \"email\": \"user@example.com\"\n}",
        "contentType": "application/json"
      },
      "name": "Update Profile",
      "method": "PUT",
      "params": [],
      "headers": [],
      "endpoint": "<<link>><<prefix>>/update",
      "responses": {},
      "testScript": "",
      "description": "Update profile user yang sedang login (name, email)",
      "preRequestScript": "",
      "requestVariables": []
    },
    {
      "v": "17",
      "id": "profile-update-password",
      "auth": {
        "authType": "inherit",
        "authActive": true
      },
      "body": {
        "body": "{\n  \"current_password\": \"passwordlama123\",\n  \"password\": \"passwordbaru123\",\n  \"password_confirmation\": \"passwordbaru123\"\n}",
        "contentType": "application/json"
      },
      "name": "Update Password",
      "method": "PUT",
      "params": [],
      "headers": [],
      "endpoint": "<<link>><<prefix>>/update-password",
      "responses": {},
      "testScript": "",
      "description": "Update password user yang sedang login",
      "preRequestScript": "",
      "requestVariables": []
    },
    {
      "v": "17",
      "id": "profile-delete",
      "auth": {
        "authType": "inherit",
        "authActive": true
      },
      "body": {
        "body": null,
        "contentType": null
      },
      "name": "Delete Account",
      "method": "DELETE",
      "params": [],
      "headers": [],
      "endpoint": "<<link>><<prefix>>/delete",
      "responses": {},
      "testScript": "",
      "description": "Menghapus akun user yang sedang login (soft delete)",
      "preRequestScript": "",
      "requestVariables": []
    }
  ],
  "headers": [],
  "variables": [
    {
      "key": "prefix",
      "initialValue": "/profile",
      "currentValue": "",
      "secret": false
    }
  ]
}
```
