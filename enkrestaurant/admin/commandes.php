<?php
session_start();
require_once "../includes/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../includes/header.php";

// Récupération des commandes
$stmt = $pdo->query("
    SELECT c.*, r.date_reservation, r.heure, cl.nom AS client_nom, cl.email
    FROM commandes c
    JOIN reservations r ON c.reservation_id = r.id
    JOIN clients cl ON c.client_id = cl.id
    ORDER BY c.date_commande DESC
");
$commandes = $stmt->fetchAll();
?>
<section class="admin-commandes-container">
    <h2>Détail des commandes</h2>

    <?php if (empty($commandes)): ?>
        <p>Aucune commande enregistrée.</p>
    <?php else: ?>
        <?php foreach ($commandes as $commande): ?>
            <div style="border:1px solid #ccc; padding:10px; margin-bottom:20px;">
                <strong>Client :</strong> <?= htmlspecialchars($commande['client_nom']) ?> (<?= $commande['email'] ?>)<br>
                <strong>Date de réservation :</strong> <?= $commande['date_reservation'] ?> à <?= substr($commande['heure'], 0, 5) ?><br>
                <strong>Date commande :</strong> <?= $commande['date_commande'] ?><br>
                <strong>Total :</strong> <?= number_format($commande['total'], 2) ?> €<br>

                <h4>Détails :</h4>
                <ul>
                    <?php
                    $stmtDetails = $pdo->prepare("
                    SELECT cd.*, m.nom
                    FROM commande_details cd
                    JOIN menu m ON cd.menu_id = m.id
                    WHERE cd.commande_id = ?
                ");
                    $stmtDetails->execute([$commande['id']]);
                    $details = $stmtDetails->fetchAll();
                    foreach ($details as $d):
                    ?>
                        <li>
                            <?= htmlspecialchars($d['nom']) ?> × <?= $d['quantite'] ?> = <?= number_format($d['sous_total'], 2) ?> €
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?php include "../includes/footer.php"; ?>