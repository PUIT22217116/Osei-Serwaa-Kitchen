const express = require('express');
const router = express.Router();
const db = require('../db');
const multer = require('multer');
const path = require('path');
const jwt = require('jsonwebtoken');
const bcrypt = require('bcrypt');
require('dotenv').config();

// Simple JWT-based admin auth for the scaffold. In production use strong secrets and HTTPS.
const JWT_SECRET = process.env.JWT_SECRET || 'change_this';

const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    const uploadBase = path.join(__dirname, process.env.UPLOAD_DIR || '../uploads');
    cb(null, uploadBase);
  },
  filename: function (req, file, cb) {
    const name = Date.now() + '-' + file.originalname.replace(/\s+/g, '-');
    cb(null, name);
  }
});
const upload = multer({ storage });

// Admin login (expects an admin table or a hardcoded user)
router.post('/login', async (req, res) => {
  const { username, password } = req.body;
  try {
    // For scaffold, check an `admins` table; fallback to a simple check
    const [rows] = await db.query('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1', [username]);
    if (rows.length === 1) {
      const admin = rows[0];
      const match = await bcrypt.compare(password, admin.password_hash);
      if (!match) return res.status(401).json({ ok: false, error: 'Invalid credentials' });
      const token = jwt.sign({ id: admin.id, username: admin.username }, JWT_SECRET, { expiresIn: '8h' });
      return res.json({ ok: true, token });
    }
    // fallback: reject
    return res.status(401).json({ ok: false, error: 'Invalid credentials' });
  } catch (e) {
    res.status(500).json({ ok: false, error: e.message });
  }
});

// middleware to verify token
function requireAuth(req, res, next) {
  const auth = req.headers.authorization || '';
  const m = auth.match(/^Bearer\s+(.*)$/i);
  if (!m) return res.status(401).json({ ok: false, error: 'Missing token' });
  const token = m[1];
  try {
    const payload = jwt.verify(token, JWT_SECRET);
    req.admin = payload;
    next();
  } catch (e) {
    res.status(401).json({ ok: false, error: 'Invalid token' });
  }
}

router.post('/upload', requireAuth, upload.single('file'), (req, res) => {
  if (!req.file) return res.status(400).json({ ok: false, error: 'No file uploaded' });
  // return web path to file
  const webPath = '/uploads/' + req.file.filename;
  res.json({ ok: true, path: webPath });
});

router.get('/stats', requireAuth, async (req, res) => {
  try {
    const [[{ total_menu }]] = await db.query('SELECT COUNT(*) as total_menu FROM menu_items');
    const [[{ total_gallery }]] = await db.query('SELECT COUNT(*) as total_gallery FROM gallery');
    res.json({ ok: true, stats: { total_menu, total_gallery } });
  } catch (e) {
    res.status(500).json({ ok: false, error: e.message });
  }
});

module.exports = router;
