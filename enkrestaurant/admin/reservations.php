<?php
session_start();
require_once "../includes/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../includes/header.php";

// MISE À JOUR DU STATUT
if (isset($_GET['changer_statut']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $nouveauStatut = $_GET['changer_statut'];

    if (in_array($nouveauStatut, ['acceptée', 'refusée', 'en attente'])) {
        $stmt = $pdo->prepare("UPDATE reservations SET statut = ? WHERE id = ?");
        $stmt->execute([$nouveauStatut, $id]);
    }

    header("Location: reservations.php");
    exit;
}

// SUPPRESSION
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: reservations.php");
    exit;
}

// Récupération des réservations
$stmt = $pdo->query("
    SELECT r.*, c.nom AS client_nom, c.email
    FROM reservations r
    JOIN clients c ON r.client_id = c.id
    ORDER BY r.date_reservation DESC, r.heure
");
$reservations = $stmt->fetchAll();
?>

<section class="admin-reservations-container">
    <h2>Réservations</h2>

    <?php if (empty($reservations)): ?>
        <p>Aucune réservation enregistrée.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Couverts</th>
                    <th>Commentaire</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td data-label="Client"><?= htmlspecialchars($r['client_nom']) ?></td>
                        <td data-label="Email"><?= htmlspecialchars($r['email']) ?></td>
                        <td data-label="Date"><?= $r['date_reservation'] ?></td>
                        <td data-label="Heure"><?= substr($r['heure'], 0, 5) ?></td>
                        <td data-label="Couverts"><?= $r['nb_personnes'] ?></td>
                        <td data-label="Commentaire"><?= nl2br(htmlspecialchars($r['commentaire'])) ?></td>
                        <td data-label="Statut"><strong><?= ucfirst($r['statut']) ?></strong></td>
                        <td data-label="Actions">
                            <?php if ($r['statut'] !== 'acceptée'): ?>
                                <a href="?changer_statut=acceptée&id=<?= $r['id'] ?>">✅ Accepter</a><br>
                            <?php endif; ?>
                            <?php if ($r['statut'] !== 'refusée'): ?>
                                <a href="?changer_statut=refusée&id=<?= $r['id'] ?>">❌ Refuser</a><br>
                            <?php endif; ?>
                            <a href="?supprimer=<?= $r['id'] ?>" onclick="return confirm('Supprimer ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php include "../includes/footer.php"; ?>
