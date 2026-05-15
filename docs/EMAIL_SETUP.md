# Panduan Setup Email Gmail dengan App Password

Dokumentasi ini menjelaskan cara mengkonfigurasi email Gmail untuk aplikasi SIPESU menggunakan App Password.

## Prasyarat

- Akun Gmail aktif
- Akses ke internet
- Aplikasi SIPESU sudah terinstall

---

## Langkah 1: Aktifkan 2-Factor Authentication (2FA)

App Password hanya bisa dibuat jika **2-Factor Authentication** sudah aktif di akun Google.

1. Buka: https://myaccount.google.com/security
2. Login dengan akun Gmail Anda
3. Scroll ke bagian **"How you sign in to Google"**
4. Klik **"2-Step Verification"**
5. Klik **"Get Started"** atau **"Turn On"**
6. Ikuti langkah-langkah untuk mengaktifkan 2FA:
   - Pilih metode verifikasi (SMS, Authenticator App, dll)
   - Verifikasi nomor telepon
   - Konfirmasi pengaktifan

---

## Langkah 2: Buat App Password

Setelah 2FA aktif, Anda bisa membuat App Password:

1. Buka: https://myaccount.google.com/apppasswords
2. Login ulang jika diminta
3. Klik **"Create new app password"** atau tombol **"+"**
4. Isi form:
   - **App name**: SIPESU (atau nama aplikasi Anda)
   - Atau pilih dari dropdown:
     - App: **Mail**
     - Device: **Windows PC** (atau sesuai device Anda)
5. Klik **"Create"**
6. Google akan menampilkan **password 16 karakter** dengan format: `abcd efgh ijkl mnop`
7. **Copy dan simpan password ini** - Anda hanya akan melihatnya sekali!

> ⚠️ **PENTING**: Simpan App Password dengan aman. Password ini hanya ditampilkan sekali dan tidak bisa dilihat lagi.

---

## Langkah 3: Konfigurasi .env Laravel

Buka file `.env` di root project Laravel Anda, lalu tambahkan/modifikasi konfigurasi berikut:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email_anda@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="email_anda@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Penjelasan Konfigurasi:

| Parameter | Nilai | Keterangan |
|-----------|-------|------------|
| `MAIL_MAILER` | smtp | Driver email |
| `MAIL_HOST` | smtp.gmail.com | Server SMTP Gmail |
| `MAIL_PORT` | 587 | Port untuk TLS |
| `MAIL_USERNAME` | email_anda@gmail.com | Alamat Gmail Anda |
| `MAIL_PASSWORD` | abcdefghijklmnop | App Password 16 karakter (tanpa spasi) |
| `MAIL_ENCRYPTION` | tls | Enkripsi TLS |
| `MAIL_FROM_ADDRESS` | email_anda@gmail.com | Alamat pengirim |
| `MAIL_FROM_NAME` | ${APP_NAME} | Nama pengirim |

---

## Langkah 4: Clear Cache

Setelah mengubah konfigurasi, jalankan perintah berikut:

```bash
php artisan config:clear
```

---

## Langkah 5: Test Pengiriman Email

1. Buka aplikasi SIPESU
2. Login sebagai Admin
3. Buka halaman **Arsip Surat**
4. Pilih salah satu surat yang statusnya **Selesai**
5. Klik tombol **"Kirim Email"**
6. Jika berhasil, akan muncul pesan: "Surat berhasil dikirim ke email xxx@gmail.com"

---

## Troubleshooting

### Error: "Username and Password not accepted"

**Penyebab:**
- App Password salah atau belum dibuat
- 2FA belum aktif
- Menggunakan password Gmail biasa (bukan App Password)

**Solusi:**
1. Pastikan 2FA sudah aktif
2. Buat App Password baru
3. Pastikan `MAIL_USERNAME` adalah alamat Gmail yang benar
4. Pastikan `MAIL_PASSWORD` adalah App Password 16 karakter (tanpa spasi)

### Error: "Connection could not be established"

**Penyebab:**
- Koneksi internet bermasalah
- Firewall memblokir port 587
- SMTP Gmail tidak bisa diakses

**Solusi:**
1. Cek koneksi internet
2. Coba gunakan port 465 dengan `MAIL_ENCRYPTION=ssl`
3. Atau gunakan Mailtrap/Mailpit untuk testing lokal

### Error: "535-5.7.8 Username and Password not accepted"

**Penyebab:**
- App Password salah
- Akun Google mengaktifkan "Less Secure App Access" (sudah tidak didukung)

**Solusi:**
1. Buat App Password baru
2. Jangan gunakan password Gmail biasa

---

## Alternatif: Mailpit untuk Development

Jika tidak ingin menggunakan Gmail untuk development, gunakan Mailpit:

### Instalasi Mailpit:

```bash
# Via npm
npm install -g mailpit

# Atau download dari GitHub Releases
# https://github.com/axllent/mailpit/releases
```

### Konfigurasi .env:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Akses Web UI:

Buka browser: http://localhost:8025

Email yang dikirim akan tertampung di Mailpit dan bisa dilihat di Web UI.

---

## Alternatif: Log Driver untuk Testing

Untuk testing tanpa mengirim email beneran:

```env
MAIL_MAILER=log
```

Email akan tersimpan di `storage/logs/laravel.log`.

---

## Keamanan

### Jangan Simpan App Password di Git

Tambahkan ke `.gitignore`:

```gitignore
.env
.env.local
.env.*.local
```

### Gunakan Environment Variable

Untuk production, gunakan environment variable:

```bash
export MAIL_PASSWORD="your-app-password"
```

### Rotate App Password secara Berkala

1. Buka https://myaccount.google.com/apppasswords
2. Hapus App Password lama
3. Buat App Password baru
4. Update konfigurasi aplikasi

---

## Referensi

- [Laravel Mail Documentation](https://laravel.com/docs/11.x/mail)
- [Google App Passwords](https://support.google.com/accounts/answer/185833)
- [Gmail SMTP Settings](https://support.google.com/mail/answer/7126229)

---

## Changelog

| Tanggal | Perubahan |
|---------|-----------|
| 2026-04-08 | Dokumentasi awal |

---

*Dokumentasi ini dibuat untuk membantu konfigurasi email pada aplikasi SIPESU.*