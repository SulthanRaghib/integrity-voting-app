# 🔐 Auth Service - Integrity Voting App

Modul **Auth Service** ini adalah tulang punggung keamanan dan manajemen akses untuk aplikasi **Integrity Voting App**. Modul ini menyediakan mekanisme autentikasi hibrida yang menggabungkan login konvensional berbasis database dengan **OAuth 2.0 (Google Login)**, yang terintegrasi penuh ke dalam panel admin **Filament**.

---

## 🌟 Fitur Utama

Modul ini dirancang untuk memberikan pengalaman login yang _seamless_ namun tetap aman.

-   **🔐 Filament Custom Authentication:** Halaman login yang dikustomisasi sepenuhnya, menggantikan tampilan default Filament untuk branding yang lebih baik.
-   **🌐 Google OAuth Integration:** Memungkinkan pengguna masuk menggunakan akun Google mereka tanpa perlu mengingat password tambahan.
-   **🔄 Auto-Provisioning User:** Jika pengguna login menggunakan Google dan emailnya belum terdaftar, akun akan dibuatkan secara otomatis (tergantung konfigurasi _Service_).
-   **🛡️ Secure Password Handling:** Hashing password otomatis dan validasi ketat pada saat pembuatan akun manual.
-   **🧩 Service Layer Pattern:** Logika bisnis autentikasi dipisahkan ke dalam `Service Class` agar _controller_ tetap bersih dan kode mudah di-_test_.

---

## 🛠️ Teknologi & Stack

Modul ini dibangun di atas fondasi teknologi berikut:

| Teknologi             | Deskripsi            | Penggunaan                                     |
| --------------------- | -------------------- | ---------------------------------------------- |
| **Laravel 12**        | Framework PHP Modern | Struktur dasar aplikasi dan routing.           |
| **Filament v4**       | Admin Panel Builder  | Menyediakan UI untuk Login dan Manajemen User. |
| **Laravel Socialite** | OAuth Library        | Menangani handshake OAuth dengan Google.       |
| **MySQL**             | Database             | Menyimpan data user dan token session.         |

---

## 📂 Struktur Direktori Auth

Berikut adalah peta lokasi file-file penting yang menyusun logika Auth Service ini:

```bash
app/
├── Filament/
│   ├── Auth/
│   │   └── CustomLogin.php          # 🎨 Logic tampilan Login Page kustom
│   └── Resources/
│       └── UserResource.php         # 👥 CRUD Manajemen User di Admin Panel
├── Http/
│   └── Controllers/
│       └── Auth/
│           └── GoogleLoginController.php # 🚦 Menangani Redirect & Callback Google
├── Services/
│   └── Auth/
│       └── GoogleAuthService.php    # 🧠 Business Logic: Menangani data user dari Google
├── Models/
│   └── User.php                     # 🗃️ Model Database User
└── Providers/
    └── Filament/
        └── AdminPanelProvider.php   # ⚙️ Mendaftarkan CustomLogin ke Panel

```

---

## ⚙️ Prasyarat Instalasi

Sebelum menjalankan fitur ini, pastikan Anda telah memiliki:

1. **Google Cloud Console Project:**

-   Aktifkan **Google+ API** atau **Google People API**.
-   Buat **OAuth Client ID** (Web Application).
-   Set **Authorized Redirect URI** ke: `https://domain-anda.com/auth/google/callback` (atau `http://localhost:8000/auth/google/callback` untuk lokal).

2. **Library Laravel Socialite:**
   Pastikan library sudah terinstall:

```bash
composer require laravel/socialite

```

---

## 🚀 Instalasi & Konfigurasi

Ikuti langkah-langkah ini untuk mengaktifkan Auth Service:

### 1. Konfigurasi Environment (.env)

Tambahkan kredensial Google Client Anda pada file `.env`:

```env
GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-google-client-secret-key
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

```

### 2. Update Database

Jalankan migrasi untuk memastikan tabel `users` memiliki kolom `google_id` dan `password` yang _nullable_ (karena login Google tidak butuh password).

```bash
php artisan migrate

```

_File migrasi terkait:_ `database/migrations/2026_01_11_130646_add_google_id_and_nullable_password_to_users_table.php`

### 3. Konfigurasi Services

Pastikan `config/services.php` sudah mendaftarkan driver google:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],

```

---

## 📖 Alur Logika (Logic Flow)

Untuk memahami bagaimana sistem bekerja di belakang layar, berikut adalah alurnya:

### A. Login dengan Google

1. **User Action:** User mengklik tombol "Login with Google" di halaman `CustomLogin`.
2. **Routing:** Request diarahkan ke `GoogleLoginController@redirectToGoogle`.
3. **Socialite:** Aplikasi mengarahkan user ke halaman izin Google.
4. **Callback:** Google mengembalikan user ke `GoogleLoginController@handleGoogleCallback`.
5. **Service Processing (`GoogleAuthService`):**

-   Menerima data user dari Google (Nama, Email, Google ID).
-   Mencari user di database berdasarkan email.
-   **Jika ada:** Update `google_id` user tersebut.
-   **Jika tidak ada:** Buat user baru dengan password acak.

6. **Authentication:** Login user secara manual menggunakan `Auth::login($user)`.
7. **Redirect:** User diarahkan masuk ke Dashboard Filament.

---

## 💻 Contoh Penggunaan Code

### 1. Menggunakan Service di Controller

Di dalam `GoogleLoginController.php`, logika bisnis diabstraksi menggunakan `GoogleAuthService`:

```php
public function handleGoogleCallback(GoogleAuthService $service)
{
    try {
        $googleUser = Socialite::driver('google')->user();

        // Logika simpan/update user ditangani oleh Service
        $user = $service->handleGoogleUser($googleUser);

        // Login session laravel
        Auth::login($user);

        return redirect()->to('/admin');
    } catch (\Exception $e) {
        return redirect()->route('filament.admin.auth.login')
            ->withErrors(['msg' => 'Login Gagal: ' . $e->getMessage()]);
    }
}

```

### 2. Kustomisasi Form Login Filament

Pada `CustomLogin.php`, kita menyuntikkan tombol Google ke dalam form login standar:

```php
// app/Filament/Auth/CustomLogin.php
public function form(Form $form): Form
{
    return $form
        ->schema([
            // ... field email & password standar ...

            // Komponen View Custom untuk tombol Google
            \Filament\Forms\Components\View::make('filament.auth.google-button'),
        ]);
}

```

---

## 🛡️ Keamanan & Best Practices

-   **Atomic Transactions:** Proses pembuatan user dalam `GoogleAuthService` sebaiknya dibungkus dalam _database transaction_ untuk mencegah data korup.
-   **Password Hashing:** User yang dibuat melalui Filament Resource `UserResource` password-nya akan di-hash otomatis sebelum disimpan (lihat method `dehydrate` pada Resource).
-   **Validasi Email:** Pastikan domain email yang diizinkan login Google divalidasi jika aplikasi ini bersifat internal (misal: hanya domain `@perusahaan.com`).

---

> **Catatan Pengembang:**
> Jika Anda mengalami masalah _Error 403_ atau _Redirect Mismatch_, periksa kembali kesesuaian antara `APP_URL` di `.env` dengan konfigurasi di Google Cloud Console. Pastikan protokol `http` vs `https` sesuai.
