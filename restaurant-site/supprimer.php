<?php
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $id = intval($_POST['id']);

    switch ($type) {
        case 'plat':
            $stmt = $pdo->prepare("DELETE FROM plats WHERE id = ?");
            break;
        case 'reservation':
            $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
            break;
        case 'commande':
            $stmt = $pdo->prepare("DELETE FROM commandes WHERE id = ?");
            break;
        default:
            die("Type non valide.");
    }

    $stmt->execute([$id]);
    header("Location: dashboard.php");
    exit();
}
