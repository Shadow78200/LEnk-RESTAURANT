<?php
require_once "includes/db.php";
include "includes/header.php";

$erreur = "";
$succes = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérif si email existe déjà
    $check = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $erreur = "Cet email est déjà utilisé.";
    } else {
        $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO clients (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $hash]);
        $succes = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
    }
}
?>
<section class="login-container">
<h2>Créer un compte client</h2>

<?php if ($erreur): ?><p style="color:red"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
<?php if ($succes): ?><p style="color:green"><?= htmlspecialchars($succes) ?></p><?php endif; ?>

<form method="POST">
    <label for="nom">Nom :</label>
    <input type="text" name="nom" required>

    <label for="email">Email :</label>
    <input type="email" name="email" required>

    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" name="mot_de_passe" required>

    <button type="submit">Créer mon compte</button>
</form>
</section>
<?php include "includes/footer.php"; ?>
