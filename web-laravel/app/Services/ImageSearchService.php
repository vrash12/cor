<?php
// app/Services/ImageSearchService.php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ImageSearchService
{
    /** Compute 64-bit aHash as a 64-char bitstring ('0'/'1') */
    public function computeHashBits(string $filePath): ?string
    {
        if (!is_file($filePath)) return null;
        $data = @file_get_contents($filePath);
        if ($data === false) return null;

        $src = @imagecreatefromstring($data);
        if (!$src) return null;

        // Resize to 8x8
        $dst = imagecreatetruecolor(8, 8);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, 8, 8, imagesx($src), imagesy($src));

        // Grayscale & collect luminance
        $sum = 0; $px = [];
        for ($y=0; $y<8; $y++) {
            for ($x=0; $x<8; $x++) {
                $rgb = imagecolorat($dst, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                // ITU-R BT.601 luma
                $l = (int) round(0.299*$r + 0.587*$g + 0.114*$b);
                $px[] = $l; $sum += $l;
            }
        }
        imagedestroy($src); imagedestroy($dst);

        $avg = $sum / 64.0;
        $bits = '';
        foreach ($px as $l) {
            $bits .= ($l >= $avg) ? '1' : '0';
        }
        return $bits; // length 64
    }

    /** Hamming distance for equal-length bitstrings */
    public function hammingDistance(string $a, string $b): int
    {
        $len = min(strlen($a), strlen($b));
        $d = 0;
        for ($i=0; $i<$len; $i++) {
            if ($a[$i] !== $b[$i]) $d++;
        }
        return $d + abs(strlen($a) - strlen($b)); // safe-guard
    }

    /** Resolve 'storage/products/..' (public URL path) to absolute filesystem path */
    public function publicPathFromImageUrl(?string $imageurl): ?string
    {
        if (!$imageurl) return null;
        // Typical: 'storage/products/xxx.png' -> public_path('storage/products/xxx.png')
        return public_path(ltrim($imageurl, '/'));
    }

    /** Cached per-product hash (24h) */
    public function getProductHashBitsCached(int $productId, string $absImagePath): ?string
    {
        $key = "product_hash_bits:{$productId}";
        return Cache::remember($key, now()->addHours(24), function () use ($absImagePath) {
            return $this->computeHashBits($absImagePath);
        });
    }
}
