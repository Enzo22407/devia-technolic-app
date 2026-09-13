<?php

// Ensure /tmp storage structure exists for Vercel Serverless environment
$tmpDirs = [
    '/tmp/framework/views',
    '/tmp/framework/sessions',
    '/tmp/framework/cache',
    '/tmp/logs'
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Copy SQLite database to /tmp if not exists
if (!file_exists('/tmp/database.sqlite') && file_exists(__DIR__ . '/../database/database.sqlite')) {
    @copy(__DIR__ . '/../database/database.sqlite', '/tmp/database.sqlite');
}

// Forward to Laravel public entrypoint
require __DIR__ . '/../public/index.php';
