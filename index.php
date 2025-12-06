<?php

/**
 * Bootstrap file for Laravel subdirectory installation
 * This file redirects requests to the public directory
 */

// Get the current request URI
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

// Remove /app prefix if present
if (strpos($requestUri, '/app') === 0) {
    $requestUri = substr($requestUri, 4);
    if (empty($requestUri)) {
        $requestUri = '/';
    }
}

// Update server variables
$_SERVER['REQUEST_URI'] = $requestUri;
$_SERVER['SCRIPT_NAME'] = '/app/public/index.php';
$_SERVER['PHP_SELF'] = '/app/public/index.php';

// Change to public directory
chdir(__DIR__ . '/public');

// Include Laravel's index.php
require __DIR__ . '/public/index.php';

