<?php
require_once "includes/db.php";
include "includes/header.php";

// Récupérer les items du menu triés par catégorie
$stmt = $pdo->query("SELECT * FROM menu ORDER BY categorie, nom");
$menuItems = $stmt->fetchAll();

// Grouper par catégorie
$grouped = [];
foreach ($menuItems as $item) {
    $grouped[$item['categorie']][] = $item;
}
?>

<section class="menu-container">

<h2>Carte du moment</h2>

<?php if (empty($menuItems)): ?>
    <p>Aucun plat ou boisson n'est encore enregistré.</p>
<?php else: ?>
    <?php foreach ($grouped as $categorie => $items): ?>
        <h3><?= ucfirst($categorie) ?>s</h3>
        <div class="menu-item-list" >
            <?php foreach ($items as $item): ?>
                <div class="menu-item" >
                    <?php if ($item['image']): ?>
                        <img src="<?= $item['image'] ?>" width="100%" style="object-fit:cover">
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($item['nom']) ?></h4>
                    <p><strong><?= number_format($item['prix'], 2) ?> €</strong></p>
                    <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</section>
<?php include "includes/footer.php"; ?>