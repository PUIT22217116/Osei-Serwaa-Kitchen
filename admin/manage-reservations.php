<?php
// Step 1: Initialize the environment and handle all PHP logic BEFORE any HTML output.
require_once __DIR__ . '/init.php';

$db = new Database();
$message = '';
$error = '';

// Step 2: Process any actions (like updating status) that might require a redirect.
if (isset($_GET['action'], $_GET['id'], $_GET['status'])) {
    $action = $_GET['action'];
    $reservation_id = (int)$_GET['id'];
    $new_status = $_GET['status'];

    // Basic validation for the status to prevent arbitrary values
    $allowed_statuses = ['confirmed', 'cancelled', 'pending', 'completed'];

    if ($action === 'update_status' && in_array($new_status, $allowed_statuses)) {
        if ($db->updateReservationStatus($reservation_id, $new_status)) {
            // Redirect to the same page but without the action parameters to prevent re-execution on refresh.
            // A success message is passed via the URL.
            header('Location: manage-reservations.php?success=status_updated');
            exit; // IMPORTANT: Always exit after a redirect header.
        } else {
            // If the update fails, we can set an error message to be displayed.
            $error = "Failed to update reservation status.";
        }
    } else {
        $error = "Invalid action or status specified.";
    }
}

// Check for success messages from the redirect
if (isset($_GET['success']) && $_GET['success'] === 'status_updated') {
    $message = "Reservation status has been updated successfully.";
}

// Step 3: Fetch data for displaying on the page.
$reservations = $db->getReservations();

// Step 4: Now that all PHP logic is done, include the HTML header.
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Manage Reservations</h3>
    </div>
    <div class="card-body">
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Date & Time</th>
                        <th>Guests</th>
                        <th>Status</th>
                        <th>Received</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reservations)): ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No reservations found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reservations as $res): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($res['id']); ?></td>
                                <td><?php echo htmlspecialchars($res['name']); ?></td>
                                <td><?php echo htmlspecialchars(date('M d, Y', strtotime($res['date']))) . ' at ' . htmlspecialchars(date('g:i A', strtotime($res['time']))); ?></td>
                                <td><?php echo htmlspecialchars($res['guests']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo htmlspecialchars($res['status']); ?>">
                                        <?php echo htmlspecialchars(ucfirst($res['status'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars(date('M d, Y', strtotime($res['created_at']))); ?></td>
                                <td class="actions">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle">Update</button>
                                        <div class="dropdown-menu">
                                            <a href="?action=update_status&id=<?php echo $res['id']; ?>&status=confirmed" class="dropdown-item">Confirm</a>
                                            <a href="?action=update_status&id=<?php echo $res['id']; ?>&status=completed" class="dropdown-item">Mark as Completed</a>
                                            <a href="?action=update_status&id=<?php echo $res['id']; ?>&status=cancelled" class="dropdown-item">Cancel</a>
                                            <a href="?action=update_status&id=<?php echo $res['id']; ?>&status=pending" class="dropdown-item">Set to Pending</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Step 5: Include the HTML footer.
require_once __DIR__ . '/footer.php';
?>