<?php
session_start();
require_once "includes/db.php";

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit;
}

include "includes/header.php";

// Récupérer les items du menu
$menuItems = $pdo->query("SELECT * FROM menu ORDER BY categorie, nom")->fetchAll();

// Soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_SESSION['client_id'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $nb_personnes = $_POST['nb_personnes'];
    $commentaire = trim($_POST['commentaire']);
    $plats = $_POST['plats'] ?? [];
    $quantites = $_POST['quantites'] ?? [];

    if (empty($plats)) {
        echo "<p style='color:red;'>Veuillez sélectionner au moins un plat ou une boisson.</p>";
    } elseif ($heure < "12:20" || $heure > "14:00") {
        echo "<p style='color:red;'>❌ Les réservations sont possibles uniquement entre 12h20 et 14h00.</p>";
    } else {
        // 1. Créer la réservation
        $stmt = $pdo->prepare("INSERT INTO reservations (client_id, date_reservation, heure, nb_personnes, commentaire) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$client_id, $date, $heure, $nb_personnes, $commentaire]);
        $reservation_id = $pdo->lastInsertId();

        // 2. Créer la commande
        $total = 0;
        $commandeDetails = [];

        foreach ($plats as $id) {
            $qte = intval($quantites[$id]);
            if ($qte > 0) {
                $stmt = $pdo->prepare("SELECT prix FROM menu WHERE id = ?");
                $stmt->execute([$id]);
                $prix = $stmt->fetchColumn();
                $sous_total = $prix * $qte;
                $commandeDetails[] = [$id, $qte, $sous_total];
                $total += $sous_total;
            }
        }

        $stmt = $pdo->prepare("INSERT INTO commandes (client_id, reservation_id, total) VALUES (?, ?, ?)");
        $stmt->execute([$client_id, $reservation_id, $total]);
        $commande_id = $pdo->lastInsertId();

        foreach ($commandeDetails as [$menu_id, $qte, $sous_total]) {
            $stmt = $pdo->prepare("INSERT INTO commande_details (commande_id, menu_id, quantite, sous_total) VALUES (?, ?, ?, ?)");
            $stmt->execute([$commande_id, $menu_id, $qte, $sous_total]);
        }

        echo "<p style='color:green;'>✅ Réservation enregistrée avec succès !</p>";
    }
}
?>

<section class="reservation-container">

    <h2>Réservation</h2>
 

    <form method="POST" onsubmit="return verifierCommande();">
        <label>Date :</label>
        <input type="date" name="date" required><br>

        <label>Heure :</label>
        <input type="time" name="heure" required min="12:20" max="14:00"><br>

        <label>Nombre de personnes :</label>
        <input type="number" name="nb_personnes" min="1" required><br>

        <label>Commentaire :</label>
        <textarea name="commentaire" placeholder="Allergies, demandes spéciales..."></textarea><br>

        <h3>Choisissez vos plats / boissons</h3>

        <?php
        // Regrouper les plats par catégorie
        $groupedMenu = [];
        foreach ($menuItems as $item) {
            $groupedMenu[$item['categorie']][] = $item;
        }
        ?>

        <?php foreach ($groupedMenu as $categorie => $items): ?>
            <h4><?= ucfirst($categorie) ?>s</h4>
            <table border="0" cellpadding="8" >
                <tr>
                    <th>Choix</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                </tr>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><input type="checkbox" name="plats[]" value="<?= $item['id'] ?>" class="platCheck" data-id="<?= $item['id'] ?>" onchange="majTotal()"></td>
                        <td><?= htmlspecialchars($item['nom']) ?></td>
                        <td><span class="prix" data-id="<?= $item['id'] ?>"><?= $item['prix'] ?></span> €</td>
                        <td><input type="number" name="quantites[<?= $item['id'] ?>]" value="0" min="0" class="quantite" data-id="<?= $item['id'] ?>" onchange="majTotal()"></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>

        <h4>Total : <span id="total">0.00</span> €</h4>

        <button type="submit">Valider la réservation</button>
    </form>
</section>
<script>
    function majTotal() {
        let total = 0;
        document.querySelectorAll('.platCheck:checked').forEach(cb => {
            const id = cb.dataset.id;
            const prix = parseFloat(document.querySelector('.prix[data-id="' + id + '"]').innerText);
            const qte = parseInt(document.querySelector('.quantite[data-id="' + id + '"]').value);
            total += prix * qte;
        });
        document.getElementById("total").innerText = total.toFixed(2);
    }

    function verifierCommande() {
        const checks = document.querySelectorAll('.platCheck:checked');
        if (checks.length === 0) {
            alert("Vous devez sélectionner au moins un plat ou une boisson.");
            return false;
        }
        return true;
    }
</script>

<?php include "includes/footer.php"; ?>