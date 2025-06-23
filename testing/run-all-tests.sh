#!/bin/bash

# Skrip utama untuk menjalankan semua tes di folder testing pada WSL

echo "Memulai semua tes..."

# Pindah ke direktori root proyek
cd "$(dirname "$0")/.."

# Tampilkan menu untuk pemilihan tes
echo "Pilih tes atau aksi yang ingin dijalankan:"
echo "1. test-speed-fixed.ps1 (Tes kecepatan Docker dengan perbaikan)"
echo "2. test-speed-simple.ps1 (Tes kecepatan sederhana)"
echo "3. test-speed.ps1 (Tes kecepatan standar)"
echo "4. test-webhook.sh (Tes webhook)"
echo "5. start-app.ps1 (Memulai aplikasi Docker)"
echo "6. Jalankan semua tes"

read -p "Masukkan pilihan Anda (1-6): " pilihan

case $pilihan in
    1)
        echo "Menjalankan test-speed-fixed.ps1..."
        /mnt/c/Windows/System32/WindowsPowerShell/v1.0/powershell.exe -ExecutionPolicy Bypass -File testing/test-speed-fixed.ps1
        ;;
    2)
        echo "Menjalankan test-speed-simple.ps1..."
        /mnt/c/Windows/System32/WindowsPowerShell/v1.0/powershell.exe -ExecutionPolicy Bypass -File testing/test-speed-simple.ps1
        ;;
    3)
        echo "Menjalankan test-speed.ps1..."
        /mnt/c/Windows/System32/WindowsPowerShell/v1.0/powershell.exe -ExecutionPolicy Bypass -File testing/test-speed.ps1
        ;;
    4)
        echo "Menjalankan test-webhook.sh..."
        bash testing/test-webhook.sh
        ;;
    5)
        echo "Menjalankan start-app.ps1..."
        /mnt/c/Windows/System32/WindowsPowerShell/v1.0/powershell.exe -ExecutionPolicy Bypass -File testing/start-app.ps1
        ;;
    6)
        echo "Menjalankan semua tes..."
        echo "Menjalankan test-speed-fixed.ps1..."
        /mnt/c/Windows/System32/WindowsPowerShell/v1.0/powershell.exe -ExecutionPolicy Bypass -File testing/test-speed-fixed.ps1
        echo "Menjalankan test-speed-simple.ps1..."
        /mnt/c/Windows/System32/WindowsPowerShell/v1.0/powershell.exe -ExecutionPolicy Bypass -File testing/test-speed-simple.ps1
        echo "Menjalankan test-speed.ps1..."
        /mnt/c/Windows/System32/WindowsPowerShell/v1.0/powershell.exe -ExecutionPolicy Bypass -File testing/test-speed.ps1
        echo "Menjalankan test-webhook.sh..."
        bash testing/test-webhook.sh
        ;;
    *)
        echo "Pilihan tidak valid. Harap pilih angka antara 1 dan 6."
        ;;
esac

echo "Eksekusi tes selesai." 