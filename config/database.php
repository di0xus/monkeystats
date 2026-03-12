<?php
$host = 'localhost';
$db = 'sae203_db';
$user = 'sae';
$pass = 'dorian';
$charset = 'utf8mb4';

define('MONKEYTYPE_APE_KEY', 'votre_cle_api');

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
