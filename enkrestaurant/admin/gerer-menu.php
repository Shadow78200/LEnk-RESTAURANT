<?php
session_start();
require_once "../includes/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../includes/header.php";

// AJOUT ou MODIF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['ajouter']) || isset($_POST['modifier']))) {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = floatval($_POST['prix']);
    $categorie = $_POST['categorie'];
    $image = "";
    $updateImage = false;

    // Upload image si modifiée
    if (!empty($_FILES['image']['name'])) {
        $nomImage = uniqid() . "_" . basename($_FILES['image']['name']);
        $chemin = "../assets/images/menu/" . $nomImage;
        move_uploaded_file($_FILES['image']['tmp_name'], $chemin);
        $image = "assets/images/menu/" . $nomImage;
        $updateImage = true;
    }

    if (isset($_POST['ajouter'])) {
        $stmt = $pdo->prepare("INSERT INTO menu (nom, description, prix, image, categorie) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $description, $prix, $image, $categorie]);
    }

    if (isset($_POST['modifier']) && isset($_POST['id'])) {
        $id = intval($_POST['id']);

        if ($updateImage) {
            $stmt = $pdo->prepare("SELECT image FROM menu WHERE id = ?");
            $stmt->execute([$id]);
            $plat = $stmt->fetch();
            if ($plat && $plat['image']) {
                $cheminOld = "../" . $plat['image'];
                if (file_exists($cheminOld)) unlink($cheminOld);
            }

            $stmt = $pdo->prepare("UPDATE menu SET nom = ?, description = ?, prix = ?, image = ?, categorie = ? WHERE id = ?");
            $stmt->execute([$nom, $description, $prix, $image, $categorie, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE menu SET nom = ?, description = ?, prix = ?, categorie = ? WHERE id = ?");
            $stmt->execute([$nom, $description, $prix, $categorie, $id]);
        }
    }

    header("Location: gerer-menu.php");
    exit;
}

// SUPPRESSION
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $stmt = $pdo->prepare("SELECT image FROM menu WHERE id = ?");
    $stmt->execute([$id]);
    $plat = $stmt->fetch();
    if ($plat && $plat['image']) {
        $cheminImage = "../" . $plat['image'];
        if (file_exists($cheminImage)) unlink($cheminImage);
    }

    $stmt = $pdo->prepare("DELETE FROM menu WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: gerer-menu.php");
    exit;
}

// RÉCUP MENU + MODIF ITEM
$menuItems = $pdo->query("SELECT * FROM menu ORDER BY categorie, nom")->fetchAll();
$modifierItem = null;

if (isset($_GET['modifier'])) {
    $idModif = intval($_GET['modifier']);
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
    $stmt->execute([$idModif]);
    $modifierItem = $stmt->fetch();
}
?>
<section class="admin-menu-container">
    <h2>Gestion du Menu</h2>
    <p>Ajoutez / modifiez / supprimez içi les plats, boissons, desserts à la carte.</p>

    <h3><?= $modifierItem ? "Modifier" : "Ajouter" ?> un plat ou une boisson</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $modifierItem['id'] ?? '' ?>">

        <input type="text" name="nom" placeholder="Nom" value="<?= $modifierItem['nom'] ?? '' ?>" required><br>
        <textarea name="description" placeholder="Description"><?= $modifierItem['description'] ?? '' ?></textarea><br>
        <input type="number" step="0.01" name="prix" placeholder="Prix (€)" value="<?= $modifierItem['prix'] ?? '' ?>" required><br>

        <label for="categorie">Catégorie :</label>
<select name="categorie" id="categorie" required>
    <?php foreach (['plat', 'boisson', 'dessert'] as $cat): ?>
        <option value="<?= $cat ?>" <?= (isset($modifierItem['categorie']) && $modifierItem['categorie'] === $cat) ? 'selected' : '' ?>>
            <?= ucfirst($cat) ?>
        </option>
    <?php endforeach; ?>
</select><br>


        <input type="file" name="image" accept="image/*"><br>
        <?php if ($modifierItem && $modifierItem['image']): ?>
            <small>Image actuelle :</small><br>
            <img src="../<?= $modifierItem['image'] ?>" width="80"><br>
        <?php endif; ?>

        <button type="submit" name="<?= $modifierItem ? 'modifier' : 'ajouter' ?>">
            <?= $modifierItem ? "Mettre à jour" : "Ajouter" ?>
        </button>
    </form>

    <h3>Menu actuel (trié par catégorie)</h3>

    <?php
    // Grouper par catégorie
    $grouped = [];
    foreach ($menuItems as $item) {
        $grouped[$item['categorie']][] = $item;
    }
    ?>

    <?php foreach ($grouped as $categorie => $items): ?>
        <h4><?= ucfirst($categorie) ?>s</h4>
        <table border="1" cellpadding="10" style="margin-bottom: 20px;">
            <!-- <tr>
                <th>Image</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Actions</th>
            </tr> -->
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php if ($item['image']): ?><img src="../<?= $item['image'] ?>" width="80"><?php endif; ?></td>
                    <td><?= htmlspecialchars($item['nom']) ?></td>
                    <td><?= nl2br(htmlspecialchars($item['description'])) ?></td>
                    <td><?= number_format($item['prix'], 2) ?> €</td>
                    <td>
                        <a href="?modifier=<?= $item['id'] ?>" class="btn-modifier"> Modifier</a><br>
                        <a href="?supprimer=<?= $item['id'] ?>" onclick="return confirm('Supprimer cet item ?')" class="btn-supprimer"> Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
</section>
<?php include "../includes/footer.php"; ?>