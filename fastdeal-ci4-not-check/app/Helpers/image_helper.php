<?php

declare(strict_types=1);

if (! function_exists('image_url')) {
    /**
     * Return a usable image URL for uploads or absolute http(s) URLs.
     */
    function image_url(?string $path): string
    {
        if ($path === null || $path === '') {
            return '';
        }

        $path = trim($path);
        if (preg_match('#^https?://#i', $path) === 1) {
            return $path;
        }

        return base_url($path);
    }
}
