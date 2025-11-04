<?php
// Note: The header file includes config.php and Database.php
require_once __DIR__ . '/header.php'; // Use the admin header

// Check if user is logged in
require_admin();

// Get statistics
$db = new Database();
$stats = [
    'total_reservations' => $db->getTotalReservations(),
    'pending_reservations' => $db->getPendingReservations(),
    'total_menu_items' => $db->getTotalMenuItems(),
    'active_menu_items' => $db->getActiveMenuItems(),
    'today_reservations' => $db->getTodayReservations(),
    'revenue_today' => $db->getRevenueToday()
];

// Get recent reservations
$recent_reservations = $db->getRecentReservations(5);
?>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            📅
        </div>
        <div class="stat-info">
            <h3 id="stat-total-reservations"><?php echo $stats['total_reservations']; ?></h3>
            <p>Total Reservations</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            ⏳
        </div>
        <div class="stat-info">
            <h3 id="stat-pending-reservations"><?php echo $stats['pending_reservations']; ?></h3>
            <p>Pending Reservations</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            🍽️
        </div>
        <div class="stat-info">
            <h3 id="stat-total-menu-items"><?php echo $stats['total_menu_items']; ?></h3>
            <p>Menu Items</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon danger">
            💰
        </div>
        <div class="stat-info">
            <h3 id="stat-revenue-today">₵<?php echo number_format($stats['revenue_today'], 2); ?></h3>
            <p>Revenue Today</p>
        </div>
    </div>
</div>

<!-- Recent Reservations -->
<div class="card">
    <div class="card-header">
        <h3>Recent Reservations</h3>
        <a href="manage-reservations.php" class="btn btn-primary btn-sm">View All</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Date & Time</th>
                        <th>Guests</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="recent-reservations-body">
                    <?php if (empty($recent_reservations)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No recent reservations found.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($recent_reservations as $reservation): ?>
                    <tr>
                        <td>#<?php echo $reservation['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($reservation['name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($reservation['email']); ?></small>
                        </td>
                        <td>
                            <?php echo date('M j, Y', strtotime($reservation['date'])); ?><br>
                            <small><?php echo date('g:i A', strtotime($reservation['time'])); ?></small>
                        </td>
                        <td><?php echo $reservation['guests']; ?> people</td>
                        <td>
                            <span class="status-badge status-<?php echo $reservation['status']; ?>">
                                <?php echo ucfirst($reservation['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="manage-reservations.php?action=view&id=<?php echo $reservation['id']; ?>" class="btn btn-outline btn-sm">View</a>
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

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h3>Quick Actions</h3>
    </div>
    <div class="card-body">
        <div class="quick-actions">
            <a href="edit-menu-item.php" class="quick-action">
                <div class="action-icon">➕</div>
                <span>Add Menu Item</span>
            </a>
            <a href="manage-gallery.php" class="quick-action">
                <div class="action-icon">🖼️</div>
                <span>Manage Gallery</span>
            </a>
            <a href="../gallery.php" class="quick-action">
                <div class="action-icon">🖼️</div>
                <span>View Gallery</span>
            </a>
            <a href="../index.php" class="quick-action">
                <div class="action-icon">🌐</div>
                <span>Visit Website</span>
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; // Use the admin footer ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statsElements = {
        totalReservations: document.getElementById('stat-total-reservations'),
        pendingReservations: document.getElementById('stat-pending-reservations'),
        totalMenuItems: document.getElementById('stat-total-menu-items'),
        revenueToday: document.getElementById('stat-revenue-today'),
        recentReservationsBody: document.getElementById('recent-reservations-body')
    };

    async function fetchStats() {
        try {
            const response = await fetch('../api/get-stats.php');
            if (!response.ok) {
                // Stop polling if we get an auth error (e.g., session expired)
                if (response.status === 403) {
                    console.warn('Stats polling stopped due to authentication error.');
                    clearInterval(pollingInterval);
                }
                return;
            }

            const data = await response.json();

            if (data.success) {
                statsElements.totalReservations.textContent = data.stats.total_reservations;
                statsElements.pendingReservations.textContent = data.stats.pending_reservations;
                statsElements.totalMenuItems.textContent = data.stats.total_menu_items;
                statsElements.revenueToday.textContent = '₵' + parseFloat(data.stats.revenue_today).toFixed(2);
                updateRecentReservations(data.stats.recent_reservations);
            }
        } catch (error) {
            console.error('Error fetching stats:', error);
        }
    }

    // Fetch stats every 15 seconds
    const pollingInterval = setInterval(fetchStats, 15000);

    function updateRecentReservations(reservations) {
        const tbody = statsElements.recentReservationsBody;
        tbody.innerHTML = ''; // Clear existing rows

        if (!reservations || reservations.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">No recent reservations found.</td></tr>';
            return;
        }

        reservations.forEach(res => {
            const date = new Date(res.date);
            const formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

            const timeParts = res.time.split(':');
            const hours = parseInt(timeParts[0], 10);
            const minutes = timeParts[1];
            const period = hours >= 12 ? 'PM' : 'AM';
            const displayHours = hours % 12 || 12;
            const formattedTime = `${displayHours}:${minutes} ${period}`;

            const row = `
                <tr>
                    <td>#${res.id}</td>
                    <td>
                        <strong>${escapeHTML(res.name)}</strong><br>
                        <small>${escapeHTML(res.email)}</small>
                    </td>
                    <td>
                        ${formattedDate}<br>
                        <small>${formattedTime}</small>
                    </td>
                    <td>${res.guests} people</td>
                    <td>
                        <span class="status-badge status-${escapeHTML(res.status)}">${escapeHTML(res.status.charAt(0).toUpperCase() + res.status.slice(1))}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="manage-reservations.php?action=view&id=${res.id}" class="btn btn-outline btn-sm">View</a>
                        </div>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    function escapeHTML(str) {
        return str.replace(/[&<>"']/g, function(match) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            }[match];
        });
    }
});
</script>