<?php

namespace App\Helpers;

class UrlHelper
{
    /**
     * Get full storage URL for an image
     * 
     * @param string|null $path
     * @param string|null $default
     * @return string|null
     */
    public static function storageUrl(?string $path, ?string $default = null): ?string
    {
        if (!$path) {
            return $default;
        }
        
        // If it's already a full URL, return as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        
        // Get base URL from config or environment
        $baseUrl = config('app.url');
        
        // Remove /api/v1 if present in base URL
        $baseUrl = str_replace('/api/v1', '', $baseUrl);
        
        // Ensure base URL doesn't end with slash
        $baseUrl = rtrim($baseUrl, '/');
        
        // Ensure path starts with slash
        $path = ltrim($path, '/');
        
        // If path already starts with 'storage/', use it as is
        if (str_starts_with($path, 'storage/')) {
            return "$baseUrl/$path";
        }
        
        // Otherwise, prepend 'storage/'
        return "$baseUrl/storage/$path";
    }
    
    /**
     * Get multiple full storage URLs
     * 
     * @param array $paths
     * @param string|null $default
     * @return array
     */
    public static function storageUrls(array $paths, ?string $default = null): array
    {
        return array_map(fn($path) => self::storageUrl($path, $default), $paths);
    }
}

