<?php
// Step 1: Initialize the admin environment and perform authentication.
require_once __DIR__ . '/init.php';

$db = new Database();

// --- Handle Actions ---

$action = $_GET['action'] ?? null;
$message_id = $_GET['id'] ?? null;

if ($message_id) {
    if ($action === 'mark_read') {
        $db->updateContactMessageStatus($message_id, 'read');
        header('Location: contact-messages.php?success=marked_read');
        exit;
    }
    if ($action === 'mark_unread') {
        $db->updateContactMessageStatus($message_id, 'unread');
        header('Location: contact-messages.php?success=marked_unread');
        exit;
    }
    if ($action === 'delete') {
        $db->deleteContactMessage($message_id);
        header('Location: contact-messages.php?success=deleted');
        exit;
    }
}

// Step 2: Now that any updates are processed, load the header.
require_once __DIR__ . '/header.php';

$messages = $db->getContactMessages();
?>

<div class="card">
    <div class="card-header">
        <h3>Contact Form Messages</h3>
    </div>
    <div class="card-body">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Action completed successfully.</div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($messages)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No messages found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($messages as $message): ?>
                            <tr class="<?php echo $message['status'] === 'unread' ? 'font-weight-bold' : ''; ?>">
                                <td>
                                    <strong><?php echo htmlspecialchars($message['name']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($message['email']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($message['subject']); ?></td>
                                <td><?php echo date('M j, Y, g:i a', strtotime($message['created_at'])); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $message['status'] === 'read' ? 'completed' : 'pending'; ?>">
                                        <?php echo ucfirst(htmlspecialchars($message['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-primary btn-sm view-message-btn" data-id="<?php echo $message['id']; ?>">View</button>
                                        <?php if ($message['status'] === 'unread'): ?>
                                            <a href="?action=mark_read&id=<?php echo $message['id']; ?>" class="btn btn-success btn-sm">Mark Read</a>
                                        <?php else: ?>
                                            <a href="?action=mark_unread&id=<?php echo $message['id']; ?>" class="btn btn-outline btn-sm">Mark Unread</a>
                                        <?php endif; ?>
                                        <a href="?action=delete&id=<?php echo $message['id']; ?>" class="btn btn-danger btn-sm btn-delete">Delete</a>
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

<!-- View Message Modal -->
<div class="modal" id="viewMessageModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalSubject"></h3>
            <span class="modal-close" id="modalClose">&times;</span>
        </div>
        <div class="modal-body">
            <div class="message-details">
                <p><strong>From:</strong> <span id="modalName"></span> &lt;<span id="modalEmail"></span>&gt;</p>
                <p><strong>Phone:</strong> <span id="modalPhone"></span></p>
                <p><strong>Date:</strong> <span id="modalDate"></span></p>
            </div>
            <hr>
            <div class="message-body">
                <pre id="modalMessageBody"></pre>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" id="modalCloseBtn">Close</button>
        </div>
    </div>
</div>

            </div> <!-- This closes the .admin-content div from header.php -->
        </main> <!-- This closes the .admin-main div from header.php -->
    </div> <!-- This closes the .admin-container div from header.php -->

<?php require_once __DIR__ . '/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation to delete buttons
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // Handle view message modal
    const modal = document.getElementById('viewMessageModal');
    const modalClose = document.getElementById('modalClose');
    const modalCloseBtn = document.getElementById('modalCloseBtn');
    let activeRow = null; // To keep track of which table row is being viewed

    document.querySelectorAll('.view-message-btn').forEach(button => {
        button.addEventListener('click', function() {
            const messageId = this.dataset.id;
            activeRow = this.closest('tr'); // Store the table row

            // Fetch message details from the server
            fetch(`get-message-details.php?id=${messageId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const msg = data.message;
                        
                        // Populate modal with data
                        document.getElementById('modalSubject').textContent = msg.subject;
                        document.getElementById('modalName').textContent = msg.name;
                        document.getElementById('modalEmail').textContent = msg.email;
                        document.getElementById('modalPhone').textContent = msg.phone || 'N/A';
                        document.getElementById('modalDate').textContent = new Date(msg.created_at).toLocaleString();
                        document.getElementById('modalMessageBody').textContent = msg.message;

                        // Show the modal
                        modal.style.display = 'flex';
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    alert('An error occurred while fetching the message.');
                });
        });
    });

    // Function to close the modal
    const closeModal = () => {
        modal.style.display = 'none';
        if (activeRow) {
            // Update the UI for the row to reflect the 'read' status without a page reload
            activeRow.classList.remove('font-weight-bold');
            
            const statusBadge = activeRow.querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.textContent = 'Read';
                statusBadge.classList.remove('status-pending');
                statusBadge.classList.add('status-completed');
            }

            const markReadButton = activeRow.querySelector('.btn-success');
            if (markReadButton) {
                markReadButton.textContent = 'Mark Unread';
                markReadButton.classList.remove('btn-success');
                markReadButton.classList.add('btn-outline');
                markReadButton.href = `?action=mark_unread&id=${markReadButton.closest('.action-buttons').querySelector('.view-message-btn').dataset.id}`;
            }
            activeRow = null; // Reset active row
        }
    };

    // Add event listeners to close buttons
    modalClose.addEventListener('click', closeModal);
    modalCloseBtn.addEventListener('click', closeModal);
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });
});
</script>