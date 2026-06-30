<?php

namespace App\Support;

class StorageUrl
{
    public static function publicUrl(?string $pathOrUrl): ?string
    {
        if (!$pathOrUrl) {
            return null;
        }

        $pathOrUrl = trim($pathOrUrl);

        if (filter_var($pathOrUrl, FILTER_VALIDATE_URL)) {
            return self::forceHttps($pathOrUrl);
        }

        $path = preg_replace('/^public\//', '', $pathOrUrl);
        $path = ltrim((string) $path, '/');

        return self::forceHttps(url('/storage/' . $path));
    }

    public static function forceHttps(string $url): string
    {
        if (app()->environment('local')) {
            return $url;
        }

        return preg_replace('/^http:\/\//i', 'https://', $url);
    }

    public static function pathFromUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return preg_replace('/^public\//', '', $url);
        }

        $path = preg_replace('#^https?://[^/]+/storage/#', '', $url);

        return $path !== $url ? $path : null;
    }
}
