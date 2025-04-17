<?php
session_start();
require_once "includes/db.php";

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit;
}

include "includes/header.php";

$client_id = $_SESSION['client_id'];

// Récupérer les réservations + commandes
$stmt = $pdo->prepare("
    SELECT r.*, c.id AS commande_id, c.total, c.date_commande
    FROM reservations r
    LEFT JOIN commandes c ON r.id = c.reservation_id
    WHERE r.client_id = ?
    ORDER BY r.date_reservation DESC, r.heure DESC
");
$stmt->execute([$client_id]);
$reservations = $stmt->fetchAll();
?>
<section class="compte-container">
<h2>Mon Compte</h2>
<p>Bienvenue, <?= htmlspecialchars($_SESSION['client_nom']) ?> 👋</p>
<p>Retrouvez içi vos réservations et controlez leurs statuts :</p>

<?php if (empty($reservations)): ?>
    <p>Vous n'avez pas encore effectué de réservation.</p>
<?php else: ?>
    <?php foreach ($reservations as $r): ?>
        <div class="compte-item" >
            <strong>Date :</strong> <?= $r['date_reservation'] ?> à <?= substr($r['heure'], 0, 5) ?><br>
            <strong>Personnes :</strong> <?= $r['nb_personnes'] ?><br>
            <strong>Statut réservation :</strong>
            <?php
                if ($r['statut'] === 'en attente') echo "⏳ En attente";
                elseif ($r['statut'] === 'acceptée') echo "✅ Acceptée";
                else echo "❌ Refusée";
            ?><br>

            <?php if ($r['commentaire']): ?>
                <strong>Commentaire :</strong> <?= nl2br(htmlspecialchars($r['commentaire'])) ?><br>
            <?php endif; ?>

            <?php if ($r['commande_id']): ?>
                <strong>Commande :</strong><br>
                <ul>
                    <?php
                    $stmtDetails = $pdo->prepare("
                        SELECT cd.*, m.nom
                        FROM commande_details cd
                        JOIN menu m ON cd.menu_id = m.id
                        WHERE cd.commande_id = ?
                    ");
                    $stmtDetails->execute([$r['commande_id']]);
                    $details = $stmtDetails->fetchAll();
                    foreach ($details as $d):
                    ?>
                        <li>
                            <?= htmlspecialchars($d['nom']) ?> × <?= $d['quantite'] ?> = <?= number_format($d['sous_total'], 2) ?> €
                        </li>
                    <?php endforeach; ?>
                </ul>
                <strong>Total :</strong> <?= number_format($r['total'], 2) ?> €
            <?php else: ?>
                <p><em>Aucune commande liée à cette réservation.</em></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</section>
<?php include "includes/footer.php"; ?>
