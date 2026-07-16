<?php

/**
 * Forward requests to the public directory.
 * This file is extremely useful for shared hosting environments
 * where the document root cannot be pointed directly to the /public folder.
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Allow the built-in PHP server to serve static files directly
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

// Pass everything else to Laravel's actual entry point
require_once __DIR__.'/public/index.php';
