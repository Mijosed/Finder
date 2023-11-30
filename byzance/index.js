const express = require('express');
const app = express();
const cors = require('cors');
const { Pool } = require('pg');

app.use(cors());
app.use(express.json());

const pool = new Pool({
  user: 'postgres',
  host: 'localhost',
  database: 'byzance',
  password: 'Dorcas95c*',
  port: 5432
});

app.get('/bonjour', function(req, res){
  res.json({ message: "Yohou", methode: req.method });
});

app.get('/chambre/:id', async (req, res) => {
  const { id } = req.params;
  try {
    const client = await pool.connect();
    const result = await client.query('SELECT * FROM chambre WHERE id = $1', [id]);
    client.release();
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: 'Une erreur s\'est produite lors de la récupération des données.' });
  }
});

app.get('/disponibilites', async (req, res) => {
  const { cat, dated, datef } = req.query;
  try {
    const client = await pool.connect();
    const result = await client.query(
      'SELECT c.libelle AS categorie, r.dated AS date_debut, r.datef AS date_fin FROM reservation r INNER JOIN categorie c ON r.id = c.id WHERE c.libelle = $1 AND r.dated >= $2 AND r.datef <= $3',
      [cat, dated, datef]
    );
    client.release();
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: 'Une erreur s\'est produite lors de la récupération des données.' });
  }
});

app.delete('/user/:id', async (req, res) => {
  const { id } = req.params;
  try {
    const client = await pool.connect();
    const result = await client.query('DELETE FROM user WHERE id = $1', [id]);
    client.release();
    res.json({ message: 'Utilisateur supprimé avec succès.' });
  } catch (error) {
    res.status(500).json({ error: 'Une erreur s\'est produite lors de la suppression de l\'utilisateur.' });
  }
});

app.get('/chambre', async (req, res) => {
  try {
    const client = await pool.connect();
    const result = await client.query('SELECT * FROM chambre');
    client.release();
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: 'Une erreur s\'est produite lors de la récupération des données.' });
  }
});

app.post('/user', async (req, res) => {
  const { nom, prenom, email, mdp } = req.body;
  try {
    const client = await pool.connect();
    const result = await client.query('INSERT INTO user (nom, prenom, email, mdp) VALUES ($1, $2, $3, $4)', [
      nom,
      prenom,
      email,
      mdp
    ]);
    client.release();
    res.json({ message: 'Utilisateur ajouté avec succès.' });
  } catch (error) {
    res.status(500).json({ error: 'Une erreur s\'est produite lors de l\'ajout de l\'utilisateur.' });
  }
});

app.get('/', function(req, res){
  res.send('Bienvenue sur la page d\'accueil');
});

const hostname = 'localhost';
const port = 8080;

app.listen(port, hostname, function(){
  console.log("Mon serveur fonctionne sur http://" + hostname + ":" + port);
});

