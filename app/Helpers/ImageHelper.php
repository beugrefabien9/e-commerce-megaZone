<?php

if (!function_exists('get_image_url')) {
    /**
     * Get the correct image URL based on whether it's a local file or external URL
     *
     * @param string|null $imagePath
     * @param string $fallback
     * @return string
     */
    function get_image_url($imagePath, $fallback = '')
    {
        if (empty($imagePath)) {
            return $fallback;
        }

        // Check if it's an external URL
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }

        // It's a local file
        return asset('storage/' . $imagePath);
    }
}

if (!function_exists('is_external_url')) {
    /**
     * Check if a path is an external URL
     *
     * @param string $path
     * @return bool
     */
    function is_external_url($path)
    {
        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
    }
}
