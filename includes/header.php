<?php
// Basic configuration
$site_title = "Osei Serwaa Kitchen";
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?> - Authentic Ghanaian Cuisine</title>
    
    <!-- Favicon Links - Using absolute paths and a manifest for reliability -->
    <link rel="apple-touch-icon" sizes="180x180" href="/osei-serwa-kitchen/images/apple-touch-icon.png?v=3">
    <link rel="icon" type="image/png" sizes="32x32" href="/osei-serwa-kitchen/images/favicon-32x32.png?v=3">
    <link rel="icon" type="image/png" sizes="16x16" href="/osei-serwa-kitchen/images/favicon-16x16.png?v=3">
    <link rel="manifest" href="/osei-serwa-kitchen/site.webmanifest?v=3">
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/<?php echo pathinfo($current_page, PATHINFO_FILENAME); ?>.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <nav class="navbar">
            <div class="nav-brand">
                <a href="index.php" class="logo">Osei Serwaa Kitchen</a>
            </div>
            
            <ul class="nav-menu" id="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="menu.php" class="nav-link">Menu</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                <li class="nav-item"><a href="gallery.php" class="nav-link">Gallery</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="reservation.php" class="nav-link btn-reservation">Reservation</a></li>
            </ul>
            
            <div class="hamburger" aria-expanded="false" aria-controls="nav-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </nav>
    </header>