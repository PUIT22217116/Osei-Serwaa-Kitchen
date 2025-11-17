<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$dataFile = __DIR__ . '/../data/about.json';
$uploadsDir = __DIR__ . '/../uploads/about';
if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);

$about = [];
if (file_exists($dataFile)) {
    $about = json_decode(file_get_contents($dataFile), true) ?: [];
}

// Helper to handle uploaded image and return its web path
function handleUpload($fieldName, $uploadsDir) {
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) return null;
    $f = $_FILES[$fieldName];
    $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
    $safe = preg_replace('/[^a-z0-9\-_.]/i', '-', pathinfo($f['name'], PATHINFO_FILENAME));
    $target = $uploadsDir . '/' . $safe . '-' . time() . '.' . $ext;
    if (move_uploaded_file($f['tmp_name'], $target)) {
        // return a web-friendly path relative to site root (e.g. "uploads/about/filename.jpg")
        return 'uploads/about/' . basename($target);
    }
    return null;
}

// Update hero
$about['hero']['title'] = $_POST['hero_title'] ?? ($about['hero']['title'] ?? '');
$about['hero']['subtitle'] = $_POST['hero_subtitle'] ?? ($about['hero']['subtitle'] ?? '');

// Story
$about['story']['title'] = $_POST['story_title'] ?? ($about['story']['title'] ?? '');
$about['story']['paragraph'] = $_POST['story_paragraph'] ?? ($about['story']['paragraph'] ?? '');

// If new story image uploaded, handle it
$newStory = handleUpload('story_image', $uploadsDir);
if ($newStory) $about['story']['image'] = $newStory;

// Team members
$teamPosted = $_POST['team'] ?? [];
$about['team'] = [];
for ($i = 0; $i < 3; $i++) {
    $m = $teamPosted[$i] ?? ['name'=>'','position'=>'','bio'=>''];
    $member = [
        'name' => $m['name'] ?? '',
        'position' => $m['position'] ?? '',
        'bio' => $m['bio'] ?? '',
        'image' => $about['team'][$i]['image'] ?? ''
    ];
    // check file upload for this member
    $field = "team_image_{$i}";
    $new = handleUpload($field, $uploadsDir);
    if ($new) $member['image'] = $new;
    $about['team'][] = $member;
}

// Values
$vals = $_POST['values'] ?? [];
$about['values'] = [];
for ($i = 0; $i < 4; $i++) {
    $v = $vals[$i] ?? ['icon'=>'','title'=>'','description'=>''];
    $about['values'][] = [
        'icon' => $v['icon'] ?? '',
        'title' => $v['title'] ?? '',
        'description' => $v['description'] ?? ''
    ];
}

// Save JSON
if (file_put_contents($dataFile, json_encode($about, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    header('Location: manage-about.php?success=1');
    exit;
} else {
    header('Location: manage-about.php?success=0');
    exit;
}
