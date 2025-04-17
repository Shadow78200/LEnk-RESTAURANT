<?php
session_start();
require_once "../includes/db.php";

// Redirection si pas admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../includes/header.php";
?>
<section class="dashboard-container">

<h2>Tableau de bord Administrateur </h2>
<p>Bienvenue, <?= htmlspecialchars($_SESSION['admin_nom']) ?> !</p>
<p>Pour modifer le  menu  rendez-vous sur "Gérer le menu, pour voir et traiter les réservations sur "Voir les réservations et enfin sur "Voir les commandes retrouvez le détails des plats commandés.</p>

<ul>
    <li><a href="gerer-menu.php">Gérer le menu</a></li>
    <li><a href="reservations.php"> Voir les réservations</a></li>
    <li><a href="commandes.php"> Voir les commandes</a></li>
</ul>
</section>
<?php include "../includes/footer.php"; ?>
