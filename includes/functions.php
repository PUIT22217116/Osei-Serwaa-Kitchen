<?php
// includes/functions.php - application helper functions
function e($s){return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}

// simple flash helper
function set_flash($k,$v){if(session_status()===PHP_SESSION_NONE) session_start(); $_SESSION['flash'][$k]=$v;}
function get_flash($k){if(session_status()===PHP_SESSION_NONE) session_start(); $v=$_SESSION['flash'][$k]??null; unset($_SESSION['flash'][$k]); return $v;}

/**
 * Send a text message via WhatsApp Cloud API.
 * Returns an array with keys: success (bool), http_code (int), response (string).
 */
function send_whatsapp_message($to_number, $message)
{
	// Require configuration constants: WA_ACCESS_TOKEN, WA_PHONE_NUMBER_ID
	if (!defined('WA_ENABLE') || !WA_ENABLE) {
		return ['success' => false, 'http_code' => 0, 'response' => 'WhatsApp sending disabled in config'];
	}
	if (empty(WA_ACCESS_TOKEN) || empty(WA_PHONE_NUMBER_ID)) {
		return ['success' => false, 'http_code' => 0, 'response' => 'WhatsApp credentials not configured'];
	}

	$url = 'https://graph.facebook.com/v17.0/' . WA_PHONE_NUMBER_ID . '/messages';

	$payload = json_encode([
		'messaging_product' => 'whatsapp',
		'to' => $to_number,
		'type' => 'text',
		'text' => [
			'preview_url' => false,
			'body' => $message
		]
	]);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Authorization: Bearer ' . WA_ACCESS_TOKEN,
		'Content-Type: application/json'
	]);

	$response = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$err = curl_error($ch);
	curl_close($ch);

	if ($response === false) {
		return ['success' => false, 'http_code' => $http_code, 'response' => $err];
	}

	$decoded = json_decode($response, true);
	$ok = ($http_code >= 200 && $http_code < 300 && isset($decoded['messages']));

	return ['success' => $ok, 'http_code' => $http_code, 'response' => $response];
}
