# API News App Project Documentation

## 1. Introduction
News App API is a Laravel-based application that provides API services to manage news. This API allows users to add, edit, delete, and retrieve news.

## 2. Technology
- Laravel (versi 12)
- PHP (minimal 8.3)
- MySQL as a database
- Composer as a dependency manager
- Laravel Sanctum for authentication API

## 3. Instalation
### 3.1. Clone Repository
```bash
git clone https://github.com/akhmad-ardi/news_app_api.git
cd news_app_api
```

### 3.2. Instal Dependensi
```bash
composer install
```

### 3.3. Environment Configuration
Create file `.env` from `.env.example` and set database configuration:
```bash
cp .env.example .env
```
Edit file `.env` to customize database and other settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=news_app
DB_USERNAME=root
DB_PASSWORD=
```

### 3.4. Generate Key & Jalankan Migrasi
```bash
php artisan key:generate
php artisan migrate --seed
```

### 3.5. Jalankan Server Laravel
```bash
php artisan serve
```
The API is now accessible via `http://127.0.0.1:8000`.

## 4. Struktur Folder
```
news-app-api/
│-- app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   ├── Models/
│-- database/
│-- routes/
│   ├── api.php
│-- .env
│-- composer.json
│-- artisan
```

## 5. Endpoint API

### 5.1. Autentikasi
| Metode | Endpoint           | Deskripsi         | Parameter |
|--------|--------------------|-------------------|-----------|
| POST   | /api/register      | Registrasi user  | name, email, password |
| POST   | /api/login         | Login user       | email, password |
| POST   | /api/logout        | Logout user      | - |

### 5.2. Berita
| Metode | Endpoint           | Deskripsi         | Parameter |
|--------|--------------------|-------------------|-----------|
| GET    | /api/news          | Mendapatkan semua berita | - |
| POST   | /api/news          | Menambahkan berita baru | title, content |
| GET    | /api/news/{id}     | Mendapatkan berita spesifik | id |
| PUT    | /api/news/{id}     | Mengupdate berita | id, title, content |
| DELETE | /api/news/{id}     | Menghapus berita | id |

## 6. Middleware & Autentikasi
- Gunakan **Laravel Sanctum/Passport** untuk melindungi endpoint tertentu.
- Contoh middleware pada route:
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/news', [NewsController::class, 'store']);
    Route::put('/news/{id}', [NewsController::class, 'update']);
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);
});
```

## 7. Testing API
Gunakan **Postman** atau **cURL** untuk menguji API. Contoh uji endpoint login dengan cURL:
```bash
curl -X POST http://127.0.0.1:8000/api/login \
     -H "Content-Type: application/json" \
     -d '{"email":"user@example.com", "password":"password123"}'
```

## 8. Conclusion
News App API adalah aplikasi sederhana untuk mengelola berita menggunakan Laravel. API ini sudah mendukung fitur CRUD dengan autentikasi menggunakan Laravel Sanctum/Passport. Anda dapat memperluas fitur API ini sesuai kebutuhan.

