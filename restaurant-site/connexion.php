<?php
$host = 'localhost';
$dbname = 'hm_resto';
$user = 'root';
$password = 'root';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    // Affiche un message si la connexion est réussie
    echo "Connexion réussie à la base de données !";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
