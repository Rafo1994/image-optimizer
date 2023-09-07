<?php

parse_str(implode('&', array_slice($argv, 1)), $_GET);

if (!isset($_GET['directory']) || $_GET['directory'] === '') {
    die('Missing directory' . PHP_EOL);
}

$dir = $_GET['directory'];

if (!is_dir($dir)) {
    die($dir . ' is not a directory' . PHP_EOL);
}

if (!str_ends_with($dir, '/')) {
    $dir .= '/';
}

$files = array_diff(scandir($dir), ['.', '..']);
$output_folder = $dir . 'optimized-' . date('Y-m-d_H-i-s') . '/';

mkdir($output_folder);

require __DIR__ . "/ImageOptimizer.php";


foreach ($files as $file) {
    if (is_dir($dir . $file)) {
        continue;
    }

    $imageInfo = getimagesize($dir . $file);

    if (!$imageInfo) {
        continue;
    }

    $image = new ImageOptimizer($dir, $file, $imageInfo, $output_folder);
    $imageIdentifier = $image->initializeImage();

    if (!$imageIdentifier) {
        continue;
    }

    $resizedImage = $image->resizeImage($imageIdentifier);
    $image->convertToWebp($resizedImage);

    imagedestroy($imageIdentifier);
    echo "Optimized $file" . PHP_EOL;
}
