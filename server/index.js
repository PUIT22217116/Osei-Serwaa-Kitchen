require('dotenv').config();
const path = require('path');
const express = require('express');
const cors = require('cors');
const apiRouter = require('./routes/api');
const adminRouter = require('./routes/admin');

const app = express();
const PORT = process.env.PORT || 4000;

app.use(cors({ origin: true, credentials: true }));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// serve uploaded files
const uploadDir = path.join(__dirname, process.env.UPLOAD_DIR || '../uploads');
app.use('/uploads', express.static(uploadDir));

app.use('/api', apiRouter);
app.use('/api/admin', adminRouter);

app.get('/', (req, res) => res.json({ ok: true, msg: 'Osei-Serwaa Kitchen API' }));

app.listen(PORT, () => console.log(`Server listening on port ${PORT}`));
