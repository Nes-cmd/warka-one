<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CspHashGenerator
{
    public static function generateHash(string $content): string
    {
        return "'sha256-" . base64_encode(hash('sha256', $content, true)) . "'";
    }

    public static function getCommonStyleHashes(): array
    {
        return [
            self::generateHash('position: relative; height: 100%; width: 100%;'),
            self::generateHash('height: 240px; width: 100%; object-fit: cover;'),
            // Hash from the error message - likely from Inertia.js or React
            "'sha256-DutKTcZmXxs6l7KVICsCfjmLThKUFRYqvu9ydnZa8Rc='",
            // Add more common hashes as they appear
            "'sha256-C9V1DQAypdgFKP+aXmOvG8NkP0Q8zp3aRaqTqvP41Bc='",
            "'sha256-fh679sLQIavz2E4Klml/jmkNQSMt5zG0zv9EJ5jtzAQ='",
        ];
    }

    /**
     * Get SRI (Subresource Integrity) hashes for external CDN resources
     * These hashes ensure external scripts haven't been tampered with
     */
    public static function getSriHashes(): array
    {
        return [
            // Alpine.js 3.13.5 from cdnjs.cloudflare.com
            'alpinejs' => [
                'url' => 'https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.js',
                'integrity' => 'sha384-i4ER82fbwIxLr8kgnfV/JkETYbBfAYvwnBVDRIi8U8+flnn+vmCWh8fxnFBopd51',
            ],
            // Lottie/bodymovin 5.12.2 from cdnjs.cloudflare.com
            'lottie' => [
                'url' => 'https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js',
                'integrity' => 'sha384-J8C0MvgX4WP58J4N2W99vCKd2J6z99ynOJ5bEfE6jeP7kVTW1drYtv/jzrxM5jbm',
            ],
            // Tailwind CSS from cdn.tailwindcss.com
            'tailwindcss' => [
                'url' => 'https://cdn.tailwindcss.com',
                'integrity' => 'sha384-igm5BeiBt36UU4gqwWS7imYmelpTsZlQ45FZf+XBn9MuJbn4nQr7yx1yFydocC/K',
            ],
        ];
    }

    /**
     * Generate SRI hash for a given URL (for development/maintenance)
     */
    public static function generateSriHash(string $url): string
    {
        $content = file_get_contents($url);
        if ($content === false) {
            throw new \Exception("Could not fetch content from: {$url}");
        }
        
        return 'sha384-' . base64_encode(hash('sha384', $content, true));
    }
} 