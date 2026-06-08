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
  "auth": {
    "authType": "bearer",
    "token": "<<token>>",
    "authActive": true
  },
  "headers": [],
  "variables": [
    {
      "key": "prefix",
      "initialValue": "/api",
      "currentValue": "",
      "secret": false
    }
  ]
}
```

## Catatan
- Gunakan `authType: "inherit"` untuk menggunakan auth dari collection parent
- Variable `prefix` dengan nilai `/api` akan otomatis ditambahkan ke `<<link>>`
