<?php
require_once 'connexion.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard HR Resto</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { margin-top: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h1>Dashboard - HR Resto 🍽️</h1>

    <!-- 🥘 Plats -->
    <h2>Menu</h2>
    <?php
    $plats = $pdo->query("SELECT * FROM plats")->fetchAll(PDO::FETCH_ASSOC);
    if ($plats): ?>
        <table>
            <tr><th>ID</th><th>Nom</th><th>Description</th><th>Prix</th><th>Disponible</th></tr>
            <?php foreach ($plats as $plat): ?>
                <tr>
                    <td><?= $plat['id'] ?></td>
                    <td><?= $plat['nom'] ?></td>
                    <td><?= $plat['description'] ?></td>
                    <td><?= $plat['prix'] ?> €</td>
                    <td><?= $plat['disponible'] ? 'Oui' : 'Non' ?></td>
<td>
    <form method="post" action="supprimer.php" onsubmit="return confirm('Supprimer ce plat ?');">
        <input type="hidden" name="type" value="plat">
        <input type="hidden" name="id" value="<?= $plat['id'] ?>">
        <button type="submit" style="background:red; color:white; border:none; padding:5px 10px; border-radius:5px;">🗑️ Supprimer</button>
    </form>
</td>

                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucun plat disponible.</p>
    <?php endif; ?>


    <!-- 📅 Réservations -->
    <h2>Réservations</h2>
    <?php
    $reservations = $pdo->query("SELECT * FROM reservations")->fetchAll(PDO::FETCH_ASSOC);
    if ($reservations): ?>
        <table>
            <tr><th>ID</th><th>Nom client</th><th>Date</th><th>Heure</th><th>Personnes</th></tr>
            <?php foreach ($reservations as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= $r['nom_client'] ?></td>
                    <td><?= $r['date_reservation'] ?></td>
                    <td><?= $r['heure'] ?></td>
                    <td><?= $r['nb_personnes'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucune réservation.</p>
    <?php endif; ?>


    <!-- 🧾 Commandes -->
    <h2>Commandes</h2>
    <?php
    $commandes = $pdo->query("SELECT * FROM commandes")->fetchAll(PDO::FETCH_ASSOC);
    if ($commandes): ?>
        <table>
            <tr><th>ID</th><th>Nom client</th><th>Date</th><th>Total</th><th>Statut</th></tr>
            <?php foreach ($commandes as $cmd): ?>
                <tr>
                    <td><?= $cmd['id'] ?></td>
                    <td><?= $cmd['nom_client'] ?></td>
                    <td><?= $cmd['date_commande'] ?></td>
                    <td><?= $cmd['total'] ?> €</td>
                    <td><?= $cmd['statut'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucune commande.</p>
    <?php endif; ?>

</body>
</html>
