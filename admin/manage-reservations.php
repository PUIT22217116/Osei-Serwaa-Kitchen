<?php
require_once __DIR__ . '/header.php';
require_admin();

$db = new Database();

// Handle status update action
if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $reservation_id = $_POST['reservation_id'] ?? null;
    $new_status = $_POST['status'] ?? null;
    $allowed_statuses = ['pending', 'confirmed', 'cancelled', 'completed'];

    if ($reservation_id && $new_status && in_array($new_status, $allowed_statuses)) {
        $db->updateReservationStatus($reservation_id, $new_status);
        header('Location: manage-reservations.php?success=updated');
        exit;
    }
}

$reservations = $db->getReservations();
?>

<div class="card">
    <div class="card-header">
        <h3>All Customer Reservations</h3>
    </div>
    <div class="card-body">
        <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
            <div class="alert alert-success">Reservation status updated successfully.</div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Date & Time</th>
                        <th>Guests</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reservations)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No reservations found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td>#<?php echo $reservation['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($reservation['name']); ?></strong></td>
                                <td>
                                    <?php echo htmlspecialchars($reservation['email']); ?><br>
                                    <small><?php echo htmlspecialchars($reservation['phone']); ?></small>
                                </td>
                                <td>
                                    <?php echo date('M j, Y', strtotime($reservation['date'])); ?><br>
                                    <small><?php echo date('g:i A', strtotime($reservation['time'])); ?></small>
                                </td>
                                <td><?php echo $reservation['guests']; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo htmlspecialchars($reservation['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($reservation['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" class="status-update-form">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                            <?php
                                            $statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
                                            foreach ($statuses as $status) {
                                                $selected = ($reservation['status'] === $status) ? 'selected' : '';
                                                echo "<option value=\"$status\" $selected>" . ucfirst($status) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </form>
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
require_once __DIR__ . '/footer.php';
?>