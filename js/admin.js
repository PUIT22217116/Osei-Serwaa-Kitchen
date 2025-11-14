document.addEventListener('DOMContentLoaded', () => {
    // --- Admin Sidebar Toggle ---
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.querySelector('.admin-sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');

    if (sidebarToggle && adminSidebar && sidebarOverlay) {
        const toggleSidebar = () => {
            adminSidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        };

        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);
    }

    // --- Admin user menu toggle (click-to-open) ---
    const adminUser = document.querySelector('.admin-user');
    const userMenu = adminUser ? adminUser.querySelector('.user-menu') : null;

    if (adminUser && userMenu) {
        adminUser.addEventListener('click', (e) => {
            // toggle open state
            adminUser.classList.toggle('open');
            e.stopPropagation();
        });

        // close when clicking outside
        document.addEventListener('click', (e) => {
            if (!adminUser.contains(e.target)) {
                adminUser.classList.remove('open');
            }
        });
    }

    // --- Notifications Dropdown ---
    const notifBtn = document.getElementById('notifBtn');
    const notificationsMenu = document.getElementById('notificationsMenu');
    const notifBadge = document.getElementById('notifBadge');
    const notifList = document.getElementById('notifList');

    if (notifBtn && notificationsMenu && notifBadge && notifList) {
        // Toggle notifications menu on button click
        notifBtn.addEventListener('click', (e) => {
            notificationsMenu.classList.toggle('open');
            e.stopPropagation();
        });

        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (!notifBtn.contains(e.target) && !notificationsMenu.contains(e.target)) {
                notificationsMenu.classList.remove('open');
            }
        });

        // Fetch notifications on page load and every 30 seconds
        const fetchNotifications = () => {
            fetch('get-notifications.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notifBadge.textContent = data.unread_count;
                        
                        // Show/hide badge based on count
                        if (data.unread_count > 0) {
                            notifBadge.style.display = 'inline-block';
                        } else {
                            notifBadge.style.display = 'none';
                        }

                        // Populate notifications list
                        if (data.messages && data.messages.length > 0) {
                            notifList.innerHTML = data.messages.map(msg => {
                                const date = new Date(msg.created_at);
                                const timeStr = date.toLocaleTimeString('en-US', { 
                                    hour: '2-digit', 
                                    minute: '2-digit',
                                    hour12: true 
                                });
                                return `
                                    <div class="notif-item">
                                        <div class="notif-sender">${escapeHtml(msg.name)}</div>
                                        <div class="notif-preview">${escapeHtml(msg.subject)}</div>
                                        <div class="notif-time">${timeStr}</div>
                                    </div>
                                `;
                            }).join('');
                        } else {
                            notifList.innerHTML = '<div class="notif-item" style="padding:1rem;color:#999;text-align:center;">No new messages</div>';
                        }
                    }
                })
                .catch(err => console.error('Error fetching notifications:', err));
        };

        // Fetch on load
        fetchNotifications();
        // Fetch every 30 seconds
        setInterval(fetchNotifications, 30000);
    }

    // Helper: escape HTML to prevent XSS
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
});