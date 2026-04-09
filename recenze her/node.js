const express = require('express');
const sqlite3 = require('sqlite3').verbose();
const app = express();

const db = new sqlite3.Database('./skola.sqlite');

db.serialize(() => {
    db.run("CREATE TABLE IF NOT EXISTS aktuality (id INTEGER PRIMARY KEY, zprava TEXT)");
    
    db.run("DELETE FROM aktuality");
    
    db.run("INSERT INTO aktuality (zprava) VALUES ('🎉 Dnes jsme se stali fakultní školou!')");
    db.run("INSERT INTO aktuality (zprava) VALUES ('🤖 Naše Robo-vozítko vyhrálo krajské kolo!')");
    db.run("INSERT INTO aktuality (zprava) VALUES ('⚡ Zítra odpadá praxe z elektrotechniky.')");
});

app.use(express.static(__dirname));

app.get('/api/aktuality', (req, res) => {
    db.all("SELECT * FROM aktuality", [], (chyba, radky) => {
        if (chyba) {
            res.status(500).json({ error: chyba.message });
            return;
        }
        res.json(radky);
    });
});

app.listen(3000, () => {
    console.log('🚀 Tvůj server běží! Otevři prohlížeč na adrese: http://localhost:3000');
});