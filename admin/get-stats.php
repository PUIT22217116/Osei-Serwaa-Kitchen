<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/auth.php';

// Ensure only logged-in admins can access this data
if (!is_admin()) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit;
}

$db = new Database();

$stats = [
    'total_reservations' => $db->getTotalReservations(),
    'pending_reservations' => $db->getPendingReservations(),
    'total_menu_items' => $db->getTotalMenuItems(),
    'active_menu_items' => $db->getActiveMenuItems(),
    'today_reservations' => $db->getTodayReservations(),
    'revenue_today' => $db->getRevenueToday()
];

echo json_encode(['success' => true, 'stats' => $stats]);
?>