const express = require('express');
const mysql = require('mysql2/promise');

const app = express();
app.use(express.urlencoded({ extended: true }));

const pool = mysql.createPool({
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME,
  waitForConnections: true,
});

const esc = (s) => String(s).replace(/[&<>"]/g, (c) => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;' }[c]));

async function init() {
  for (let i = 0; i < 30; i++) {
    try {
      await pool.query(`CREATE TABLE IF NOT EXISTS notes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        text VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)`);
      return;
    } catch (e) {
      console.log('Menunggu database...', e.code);
      await new Promise((r) => setTimeout(r, 2000));
    }
  }
  process.exit(1);
}

app.get('/', async (req, res) => {
  const [rows] = await pool.query('SELECT * FROM notes ORDER BY id DESC');
  res.send(`<!doctype html><html><head><meta charset="utf-8"><title>Catatan Docker</title>
  <style>body{font-family:sans-serif;max-width:600px;margin:40px auto}li{margin:6px 0}</style></head><body>
  <h1>Catatan Docker - Mutiara Adzika islami 102062400024</h1>
  <form method="POST" action="/add"><input name="text" placeholder="Tulis catatan" required>
  <button>Tambah</button></form><ul>
  ${rows.map((r) => `<li>${esc(r.text)} <small>(${r.created_at.toISOString()})</small></li>`).join('')}
  </ul></body></html>`);
});

app.post('/add', async (req, res) => {
  await pool.query('INSERT INTO notes (text) VALUES (?)', [req.body.text]);
  res.redirect('/');
});

app.get('/up', (req, res) => res.send('ok'));

init().then(() => app.listen(3000, () => console.log('App jalan di port 3000')));
