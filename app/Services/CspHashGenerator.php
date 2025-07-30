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
} 