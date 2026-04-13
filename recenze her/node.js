const express = require('express');
const sqlite3 = require('sqlite3').verbose();
const app = express();
app.use(express.json());

const db = new sqlite3.Database('./skola.sqlite');

db.serialize(() => {
    db.run("CREATE TABLE IF NOT EXISTS recenze (id INTEGER PRIMARY KEY AUTOINCREMENT, jmeno TEXT, hra TEXT, zprava TEXT)");
});

app.use(express.static(__dirname));

app.get('/api/aktuality', (req, res) => {
    db.all("SELECT * FROM recenze", [], (chyba, radky) => {
        if (chyba) {
            res.status(500).json({ error: chyba.message });
            return;
        }
        res.json(radky);
    });
});

app.post('/api/ulozit-recenzi', (req, res) => {
    const data = req.body;
    db.run("INSERT INTO recenze (jmeno, hra, zprava) VALUES (?, ?, ?)", 
        [data.jmeno, data.hra, data.zprava], 
        (chyba) => {
            if (chyba) {
                res.status(500).json({ error: chyba.message });
                return;
            }
            res.json({ message: "Recenze úspěšně uložena!" });
        }
    );
});

app.listen(3000, () => {
    console.log('Otevři prohlížeč na adrese: http://localhost:3000');
});