Migration to React + Node (high-level)

Overview
--------
This repository now contains a scaffold for a Node/Express API server (`server/`) and a React Vite client skeleton (`client/`). The goal is a full conversion from the PHP monolith to a modern JS stack.

What's included
- `server/` - Express server scaffold with:
  - `index.js` - entry
  - `db.js` - MySQL pool using `mysql2`
  - `routes/api.js` - public endpoints (menu, gallery, contact, reservation)
  - `routes/admin.js` - simple JWT admin endpoints and upload handler (multer)
  - `.env.example` - env variables template
- `client/` - Vite + React skeleton (created on request)

Local development
-----------------
1. Install Node.js (v16+ recommended).
2. Server:
   - cd server
   - copy `.env.example` -> `.env` and fill `DB_*` and `JWT_SECRET` and `UPLOAD_DIR` (uploads will be stored in the project `uploads/` by default)
   - npm install
   - npm run dev
3. Client (once scaffold is in place):
   - cd client
   - npm install
   - npm run dev

Important notes
---------------
- The Node server expects the same MySQL schema as the PHP app (tables like `menu_items`, `gallery`, `contact_messages`, `reservations`, `admins`). If you haven't exported/imported the DB schema, create these tables or adapt the queries.
- This scaffold uses JWT for admin auth. To migrate admin accounts, populate `admins` with `username` and `password_hash` created using bcrypt.
- File uploads are stored in `uploads/` and served at `/uploads/<filename>`; ensure the folder is writable by the app.
- Deployment: choose a host that supports Node (Render, Railway, Fly, DigitalOcean App Platform, etc.). The React build can be served as static files or hosted on a static host while the API runs on Node.

Next steps I can do for you
- Complete the `client/` scaffold (I can create a Vite app and example pages) and wire a demo to `/api/menu`.
- Implement more admin endpoints (menu management, gallery management, reservations list) and migration scripts to import data from the existing MySQL database.
- Add Dockerfiles and a small CI/CD guide to deploy to a Node-capable host.

Which of the above do you want me to do next?
