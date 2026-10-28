# Menjalankan 9Router dengan Railway

9Router dijalankan sebagai service terpisah dari aplikasi Laravel. Setelah service
dibuat, Railway akan menjalankannya kembali otomatis saat deploy/restart.

## Service 9Router

1. Buat service Railway baru dari Docker image `decolua/9router:latest`.
2. Tambahkan volume yang di-mount ke `/app/data`.
3. Tambahkan variable `DATA_DIR=/app/data`.
4. Atur service agar mendengarkan port `20128`. Buat domain publik hanya jika
   diperlukan untuk membuka dashboard, dan gunakan password dashboard yang kuat.
5. Buka dashboard, hubungkan provider AI, buat/copy API key, lalu catat model ID
   yang tersedia.

Jangan arahkan domain publik aplikasi Laravel ke service 9Router. Laravel harus
mengakses API melalui private network Railway.

## Hubungkan Laravel

Tambahkan variables berikut ke service Laravel di Railway. Ganti nama host sesuai
private domain service 9Router di project Railway:

```env
AI_ROUTER_BASE_URL=http://9router.railway.internal:20128/v1
AI_ROUTER_API_KEY=<API key dari dashboard 9Router>
AI_ROUTER_MODEL=<model ID yang tersedia di 9Router>
```

Buat API key dari menu **Endpoint & Key** 9Router. Jangan gunakan credential
provider sebagai API key aplikasi. Simpan key hanya di Variables service Laravel;
jangan kirim melalui chat atau commit ke repository. Jika key pernah dibagikan,
cabut key tersebut dan buat key baru. Setelah variables tersimpan, deploy ulang
service Laravel agar konfigurasi cache memuat nilainya.

Semua fitur generatif di AI Assistant dosen menggunakan 9Router sebagai
satu-satunya provider AI. Jika konfigurasi 9Router tidak tersedia atau gagal,
aplikasi menampilkan pesan error yang jelas dan tidak diam-diam mengirim prompt
ke provider lain. Prediksi EWS yang berbasis aturan/data lokal tidak menggunakan
model AI eksternal.

PDF teks diekstrak langsung. Halaman PDF yang berisi scan atau hanya sedikit
teks akan dibaca dengan OCR bahasa Indonesia dan Inggris. Dockerfile aplikasi
memasang Poppler dan Tesseract beserta data bahasa tersebut. Jika aplikasi
dijalankan tanpa Docker, pasang `pdftoppm`, Tesseract, dan data bahasa
`ind`/`eng` pada server agar PDF hasil pindai dapat diproses.

## Menjalankan lokal dengan Docker

```sh
docker compose -f compose.9router.yaml up -d
```

Siapkan file `.env` Laravel berisi minimal `APP_KEY` yang valid. Jalankan stack
tersebut, lalu buka aplikasi di `http://localhost:8080` dan dashboard 9Router di
`http://localhost:20128`. Port dashboard hanya di-bind ke komputer lokal.

Di dashboard 9Router, hubungkan provider dan buat API key. Kemudian tambahkan
`AI_ROUTER_API_KEY` dan `AI_ROUTER_MODEL` ke `.env`, lalu jalankan ulang app:

```sh
docker compose -f compose.9router.yaml up -d --force-recreate app
```

Laravel di Docker menggunakan alamat internal `http://9router:20128/v1`; jangan
ganti dengan `localhost`, karena tiap service berjalan di container berbeda.
