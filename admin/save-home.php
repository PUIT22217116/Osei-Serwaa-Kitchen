<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$dataFile = __DIR__ . '/../data/home.json';
$uploadsDir = __DIR__ . '/../uploads/home';
if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);

$home = [];
if (file_exists($dataFile)) {
    $home = json_decode(file_get_contents($dataFile), true) ?: [];
}

function handleUpload($fieldName, $uploadsDir) {
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) return null;
    $f = $_FILES[$fieldName];
    $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
    $safe = preg_replace('/[^a-z0-9\-_.]/i', '-', pathinfo($f['name'], PATHINFO_FILENAME));
    $target = $uploadsDir . '/' . $safe . '-' . time() . '.' . $ext;
    if (move_uploaded_file($f['tmp_name'], $target)) {
        return 'uploads/home/' . basename($target);
    }
    return null;
}

$slides = [];
for ($i = 0; $i < 5; $i++) {
    $title = $_POST["slide_title_{$i}"] ?? '';
    $subtitle = $_POST["slide_subtitle_{$i}"] ?? '';
    $existing = $home['hero'][$i]['image'] ?? '';
    $newImage = handleUpload("slide_image_{$i}", $uploadsDir);
    $img = $newImage ?: $existing;
    if (empty($img) && empty($title) && empty($subtitle)) {
        // skip empty slide
        continue;
    }
    $slides[] = [
        'image' => $img,
        'title' => $title,
        'subtitle' => $subtitle
    ];
}

$home['hero'] = $slides;

if (file_put_contents($dataFile, json_encode($home, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    header('Location: manage-home.php?success=1');
    exit;
} else {
    header('Location: manage-home.php?success=0');
    exit;
}
