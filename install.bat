@echo off
chcp 65001 > nul
color 1F
echo.
echo ========================================================
echo   SIMAS ASET - SMKN 11 Kota Tangerang
echo   Script Instalasi Otomatis untuk Windows + Laragon
echo ========================================================
echo.

:: Cek apakah kita di folder yang benar
if not exist "app\Http\Controllers\AuthController.php" (
    echo [ERROR] Jalankan script ini dari dalam folder project simas-aset!
    echo Contoh: cd C:\laragon\www\simas-aset
    pause
    exit /b 1
)

echo [1/7] Menyalin file konfigurasi .env ...
if not exist ".env" (
    copy .env.example .env
    echo       .env berhasil dibuat.
) else (
    echo       .env sudah ada, dilewati.
)
echo.

echo [2/7] Generate APP_KEY ...
php artisan key:generate
echo.

echo [3/7] Install dependencies Composer ...
composer install --no-interaction
echo.

echo [4/7] Install package QR Code ...
composer require simplesoftwareio/simple-qrcode --no-interaction
echo.

echo [5/7] Menjalankan migrasi database ...
echo.
echo [PENTING] Pastikan:
echo  - Laragon sudah Start All (Apache + MySQL aktif)
echo  - Database 'simas_aset_smkn11' sudah dibuat di phpMyAdmin
echo  - File .env sudah diisi DB_DATABASE=simas_aset_smkn11
echo.
set /p LANJUT="Sudah siap? Tekan ENTER untuk lanjut migrasi..."
php artisan migrate --seed
echo.

echo [6/7] Membuat link storage untuk foto aset ...
php artisan storage:link
echo.

echo [7/7] Membersihkan cache ...
php artisan optimize:clear
echo.

echo ========================================================
echo   INSTALASI SELESAI!
echo ========================================================
echo.
echo   Buka browser dan akses:
echo   http://simas-aset.test  atau  http://localhost/simas-aset/public
echo.
echo   Akun login default:
echo   Admin    : admin    / admin123
echo   Operator : operator / operator123
echo   Viewer   : viewer   / viewer123
echo.
echo ========================================================
echo.
pause
