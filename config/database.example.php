<?php
// Copier ce fichier en config/database.php et remplir les valeurs
define('MONKEYTYPE_APE_KEY', 'YOUR_APE_KEY_HERE');

$dsn = 'mysql:host=localhost;dbname=sae203_db;charset=utf8mb4';
return new PDO($dsn, 'sae', 'YOUR_PASSWORD_HERE', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
