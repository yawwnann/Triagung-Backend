# Skrip utama untuk menjalankan semua tes di folder testing
Write-Host "Memulai semua tes..."

# Pindah ke direktori yang benar
cd $PSScriptRoot/..

# Tampilkan menu untuk pemilihan tes
Write-Host "Pilih tes atau aksi yang ingin dijalankan:"
Write-Host "1. test-speed-fixed.ps1 (Tes kecepatan Docker dengan perbaikan)"
Write-Host "2. test-speed-simple.ps1 (Tes kecepatan sederhana)"
Write-Host "3. test-speed.ps1 (Tes kecepatan standar)"
Write-Host "4. test-webhook.sh (Tes webhook)"
Write-Host "5. start-app.ps1 (Memulai aplikasi Docker)"
Write-Host "6. Jalankan semua tes"

$pilihan = Read-Host "Masukkan pilihan Anda (1-6)"

switch ($pilihan) {
    "1" {
        Write-Host "Menjalankan test-speed-fixed.ps1..."
        ./testing/test-speed-fixed.ps1
    }
    "2" {
        Write-Host "Menjalankan test-speed-simple.ps1..."
        ./testing/test-speed-simple.ps1
    }
    "3" {
        Write-Host "Menjalankan test-speed.ps1..."
        ./testing/test-speed.ps1
    }
    "4" {
        Write-Host "Menjalankan test-webhook.sh..."
        ./testing/test-webhook.sh
    }
    "5" {
        Write-Host "Menjalankan start-app.ps1..."
        ./testing/start-app.ps1
    }
    "6" {
        Write-Host "Menjalankan semua tes..."
        Write-Host "Menjalankan test-speed-fixed.ps1..."
        ./testing/test-speed-fixed.ps1
        Write-Host "Menjalankan test-speed-simple.ps1..."
        ./testing/test-speed-simple.ps1
        Write-Host "Menjalankan test-speed.ps1..."
        ./testing/test-speed.ps1
        Write-Host "Menjalankan test-webhook.sh..."
        ./testing/test-webhook.sh
    }
    default {
        Write-Host "Pilihan tidak valid. Harap pilih angka antara 1 dan 6."
    }
}

Write-Host "Eksekusi tes selesai." 