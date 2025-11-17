const express = require('express');
const router = express.Router();
const db = require('../db');

// Public endpoints
router.get('/menu', async (req, res) => {
  try {
    const [rows] = await db.query('SELECT id, name, description, price, image FROM menu_items ORDER BY id ASC');
    res.json({ ok: true, data: rows });
  } catch (e) {
    res.status(500).json({ ok: false, error: e.message });
  }
});

router.get('/gallery', async (req, res) => {
  try {
    const [rows] = await db.query('SELECT id, title, image, category FROM gallery ORDER BY id DESC');
    res.json({ ok: true, data: rows });
  } catch (e) {
    res.status(500).json({ ok: false, error: e.message });
  }
});

router.post('/contact', async (req, res) => {
  const { name, email, message } = req.body;
  try {
    await db.query('INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())', [name, email, message]);
    res.json({ ok: true, msg: 'Message received' });
  } catch (e) {
    res.status(500).json({ ok: false, error: e.message });
  }
});

router.post('/reservation', async (req, res) => {
  const { name, phone, date, time, guests } = req.body;
  try {
    await db.query('INSERT INTO reservations (name, phone, date, time, guests, created_at) VALUES (?, ?, ?, ?, ?, NOW())', [name, phone, date, time, guests]);
    res.json({ ok: true, msg: 'Reservation received' });
  } catch (e) {
    res.status(500).json({ ok: false, error: e.message });
  }
});

module.exports = router;
