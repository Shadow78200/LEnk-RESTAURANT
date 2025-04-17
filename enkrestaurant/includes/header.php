<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>En'k Restaurant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="/assets/css/accueil.css">
    <link rel="stylesheet" href="/assets/css/menu.css">
    <link rel="stylesheet" href="/assets/css/reservation.css">
    <link rel="stylesheet" href="/assets/css/compte.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/admin-menu.css">
    <link rel="stylesheet" href="/assets/css/admin-reservations.css">
    <link rel="stylesheet" href="/assets/css/admin-commandes.css">
    <link rel="stylesheet" href="/assets/css/login.css">










    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <div class="header-top">
        <div class="header-left">
        <a href="/index.php"> <img src="/assets/images/enklogo.png" alt="logo enk" class="header-logo"></a>
           
            <h1>Restaurant pédagogique</h1>
        </div>
        <div class="burger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <nav class="header-nav" id="nav">
        <a href="/index.php">Accueil</a>
        <a href="/menu.php">Menu</a>
        <?php if (!isset($_SESSION['client_id']) && !isset($_SESSION['admin_id'])): ?>
            <a href="/register.php">Créer un compte</a>
            <a href="/login.php">Se connecter</a>
        <?php elseif (isset($_SESSION['client_id'])): ?>
            <a href="/reservation.php">Réserver</a>
            <a href="/mon-compte.php">Mon compte</a>
            <a href="/logout.php">Se déconnecter</a>
        <?php elseif (isset($_SESSION['admin_id'])): ?>
            <a href="/admin/dashboard.php">Admin</a>
            <a href="/logout.php">Se déconnecter</a>
        <?php endif; ?>
    </nav>
</header>


<script>
function toggleMenu() {
    document.getElementById("nav").classList.toggle("active");
}
</script>
