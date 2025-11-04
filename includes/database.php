<?php
class Database {
    private $pdo;

    public function __construct() {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch(PDOException $e) {
            // If the error indicates the database does not exist (SQLSTATE 42000 / error 1049),
            // provide a helpful message with a link to the setup script.
            $msg = $e->getMessage();
            $code = $e->getCode();
            if ($code === '1049' || stripos($msg, 'Unknown database') !== false) {
                // Friendly instruction for local XAMPP users
                die("Connection failed: Unknown database '" . htmlspecialchars(DB_NAME) . "'.<br>" .
                    "To create the database and tables, open <a href=\'../setup.php\'>setup.php</a> in your browser or create the database via phpMyAdmin.\n");
            }

            die("Connection failed: " . $msg);
        }
    }

    /**
     * A helper method to run queries.
     * Handles cases where tables might not exist yet during development.
     */
    private function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            // SQLSTATE '42S02' is "Base table or view not found".
            // If the table doesn't exist, we return null to avoid crashing.
            if ($e->getCode() === '42S02') {
                return null;
            }
            // For other errors, it's better to fail loudly.
            throw $e;
        }
    }

    // Reservation Methods
    public function createReservation($data) {
        $sql = "INSERT INTO reservations (name, email, phone, guests, date, time, occasion, notes, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['guests'],
            $data['date'],
            $data['time'],
            $data['occasion'],
            $data['notes']
        ]);
    }

    public function getReservations($limit = null) {
        $sql = "SELECT * FROM reservations ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function getReservationById($id) {
        $sql = "SELECT * FROM reservations WHERE id = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt ? $stmt->fetch() : null;
    }

    public function updateReservationStatus($id, $status) {
        $sql = "UPDATE reservations SET status = ? WHERE id = ?";
        // Use pdo->prepare directly as this is an update action
        // and we want it to fail if the table doesn't exist.
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    // Menu Methods
    public function getMenuItems($category = null, $limit = null) {
        $sql = "SELECT * FROM menu_items WHERE is_active = 1";
        $params = [];
        
        if ($category && $category !== 'all') {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY id DESC";

        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }

        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function getMenuItemById($id) {
        $sql = "SELECT * FROM menu_items WHERE id = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt ? $stmt->fetch() : null;
    }

    public function createMenuItem($data) {
        $sql = "INSERT INTO menu_items (name, description, price, category, image, tags, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['category'],
            $data['image'],
            $data['tags'],
            $data['is_active']
        ]);
    }

    public function updateMenuItem($id, $data) {
        $sql = "UPDATE menu_items SET name = ?, description = ?, price = ?, category = ?, 
                image = ?, tags = ?, is_active = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['category'],
            $data['image'],
            $data['tags'],
            $data['is_active'],
            $id
        ]);
    }

    public function deleteMenuItem($id) {
        $sql = "DELETE FROM menu_items WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getActiveMenuItemsData() {
        $sql = "SELECT * FROM menu_items WHERE is_active = 1 ORDER BY category, name";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }

    // Statistics Methods
    public function getTotalReservations() {
        $stmt = $this->query("SELECT COUNT(*) FROM reservations");
        return $stmt ? $stmt->fetchColumn() : 0;
    }

    public function getPendingReservations() {
        $stmt = $this->query("SELECT COUNT(*) FROM reservations WHERE status = 'pending'");
        return $stmt ? $stmt->fetchColumn() : 0;
    }

    public function getTotalMenuItems() {
        $stmt = $this->query("SELECT COUNT(*) FROM menu_items");
        return $stmt ? $stmt->fetchColumn() : 0;
    }

    public function getActiveMenuItems() {
        $stmt = $this->query("SELECT COUNT(*) FROM menu_items WHERE is_active = 1");
        return $stmt ? $stmt->fetchColumn() : 0;
    }

    public function getTodayReservations() {
        $stmt = $this->query("SELECT COUNT(*) FROM reservations WHERE date = CURDATE()");
        return $stmt ? $stmt->fetchColumn() : 0;
    }

    public function getRevenueToday() {
        // This is a placeholder. A real implementation would require a
        // junction table between reservations and menu items.
        return 0;
    }

    public function getRecentReservations($limit = 5) {
        $sql = "SELECT * FROM reservations ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Admin Authentication
    public function validateAdmin($username, $password) {
        $sql = "SELECT * FROM admin_users WHERE username = ? AND is_active = 1";
        $stmt = $this->query($sql, [$username]);
        $admin = $stmt ? $stmt->fetch() : null;

        if ($admin && password_verify($password, $admin['password'])) {
            unset($admin['password']);
            return $admin;
        }
        return false;
    }

    public function updateAdminPassword($username, $new_password_hash) {
        $sql = "UPDATE admin_users SET password = ? WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$new_password_hash, $username]);
    }


    // Contact Form Submissions
    public function saveContactMessage($data) {
        $sql = "INSERT INTO contact_messages (name, email, phone, subject, message, status) 
                VALUES (?, ?, ?, ?, ?, 'unread')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['subject'],
            $data['message']
        ]);
    }

    public function getContactMessages($limit = null) {
        $sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }

    // Gallery Methods
    public function getGalleryItems() {
        $sql = "SELECT * FROM gallery_images ORDER BY created_at DESC";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function getActiveGalleryItems() {
        $sql = "SELECT id, title, description, image_path as image, category, tags
                FROM gallery_images
                WHERE is_active = 1
                ORDER BY created_at DESC";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function getGalleryImageById($id) {
        $sql = "SELECT * FROM gallery_images WHERE id = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt ? $stmt->fetch() : null;
    }

    public function createGalleryImage($data) {
        $sql = "INSERT INTO gallery_images (title, description, image_path, category, tags, is_active) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$data['title'], $data['description'], $data['image_path'], $data['category'], $data['tags'], $data['is_active']]);
    }

    public function updateGalleryImage($id, $data) {
        $sql = "UPDATE gallery_images SET title = ?, description = ?, image_path = ?, category = ?, tags = ?, is_active = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$data['title'], $data['description'], $data['image_path'], $data['category'], $data['tags'], $data['is_active'], $id]);
    }

    public function deleteGalleryImage($id) {
        $sql = "DELETE FROM gallery_images WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Gallery Category Methods
    public function getGalleryCategories() {
        $sql = "SELECT * FROM gallery_categories ORDER BY name ASC";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function getActiveGalleryCategories() {
        $sql = "SELECT * FROM gallery_categories WHERE is_active = 1 ORDER BY name ASC";
        $stmt = $this->query($sql);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function getGalleryCategoryById($id) {
        $sql = "SELECT * FROM gallery_categories WHERE id = ?";
        $stmt = $this->query($sql, [$id]);
        return $stmt ? $stmt->fetch() : null;
    }

    public function createGalleryCategory($data) {
        $sql = "INSERT INTO gallery_categories (name, slug, is_active) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$data['name'], $data['slug'], $data['is_active']]);
    }

    public function updateGalleryCategory($id, $data) {
        $sql = "UPDATE gallery_categories SET name = ?, slug = ?, is_active = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$data['name'], $data['slug'], $data['is_active'], $id]);
    }

    public function deleteGalleryCategory($id) {
        $sql = "DELETE FROM gallery_categories WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>