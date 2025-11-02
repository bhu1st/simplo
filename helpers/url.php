<?php
// helpers/url.php

if (!function_exists('base_url')) {
    /**
     * Generates the base URL for the application.
     * Can optionally append a path.
     *
     * @param string $path
     * @return string
     */
    function base_url(string $path = ''): string
    {
        // Determine scheme (http or https)
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

        // Get the host
        $host = $_SERVER['HTTP_HOST'];

        // Get the path to the subdirectory where index.php is located
        $script_path = dirname($_SERVER['SCRIPT_NAME']);

        // Clean up the script path to handle root directory case gracefully
        $base_path = rtrim($script_path, '/\\');

        // Build the base URL
        $base_url = "{$scheme}://{$host}{$base_path}";
        
        // Append the path if provided
        if (!empty($path)) {
            return $base_url . '/' . ltrim($path, '/');
        }

        return $base_url;
    }
}

if (!function_exists('asset')) {
    /**
     * Generates a URL for an asset in the public directory.
     *
     * @param string $path
     * @return string
     */
    function asset(string $path): string
    {
        return base_url($path);
    }
}