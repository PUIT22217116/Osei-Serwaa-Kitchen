<?php
// includes/functions.php - application helper functions
function e($s){return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}

// simple flash helper
function set_flash($k,$v){if(session_status()===PHP_SESSION_NONE) session_start(); $_SESSION['flash'][$k]=$v;}
function get_flash($k){if(session_status()===PHP_SESSION_NONE) session_start(); $v=$_SESSION['flash'][$k]??null; unset($_SESSION['flash'][$k]); return $v;}
