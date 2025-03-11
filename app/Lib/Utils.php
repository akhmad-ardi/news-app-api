<?php

namespace App\Lib;

class Utils
{
    public static function slug(string $string)
    {
        // 1. Ubah menjadi huruf kecil
        $string = strtolower($string);

        // 2. Ganti karakter non-alfanumerik (kecuali spasi) dengan kosong
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);

        // 3. Ganti spasi atau tanda "-" dengan satu "-"
        $string = preg_replace('/[\s-]+/', '-', $string);

        // 4. Hapus tanda "-" di awal dan akhir
        $string = trim($string, '-');

        return $string;
    }

    public static function excerpt($text, $maxLength = 100)
    {
        // Hapus spasi ekstra di awal dan akhir
        $text = trim($text);

        // Jika panjang teks sudah lebih kecil dari batas, langsung return
        if (strlen($text) <= $maxLength) {
            return $text;
        }

        // Potong teks hingga batas maksimum
        $excerpt = substr($text, 0, $maxLength);

        // Cari posisi terakhir dari spasi untuk menghindari pemotongan kata
        $lastSpace = strrpos($excerpt, ' ');

        if ($lastSpace !== false) {
            $excerpt = substr($excerpt, 0, $lastSpace);
        }

        return $excerpt . '...';
    }
}
