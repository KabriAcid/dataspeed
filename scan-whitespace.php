<?php
// scan-whitespace.php

$root = __DIR__; // Adjust to your project root if needed
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $file) {
    if ($file->isDir()) continue;
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;

    $path = $file->getRealPath();
    $content = file_get_contents($path);

    if (preg_match('/^\xEF\xBB\xBF/', $content)) {
        echo "[BOM DETECTED] $path\n";
    }

    if (preg_match('/^\s*<\?php/', $content) === 0) {
        echo "[WHITESPACE BEFORE TAG] $path\n";
    }

    if (preg_match('/\?>\s+$/', $content)) {
        echo "[TRAILING SPACE AFTER TAG] $path\n";
    }
}
