<?php
require_once "includes/db.php";
include "includes/header.php";

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifie dans les administrateurs
    $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nom'] = $admin['nom'];
        $_SESSION['admin_role'] = $admin['role'];
        header("Location: admin/dashboard.php");
        exit;
    }

    // Sinon, vérifie dans les clients
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    $client = $stmt->fetch();

    if ($client && password_verify($mot_de_passe, $client['mot_de_passe'])) {
        $_SESSION['client_id'] = $client['id'];
        $_SESSION['client_nom'] = $client['nom'];
        header("Location: mon-compte.php"); // à créer plus tard
        exit;
    }

    $erreur = "Email ou mot de passe incorrect.";
}
?>
<section class="login-container">
<h2>Connexion</h2>

<?php if ($erreur): ?><p style="color:red"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>

<form method="POST">
    <label for="email">Email :</label>
    <input type="email" name="email" required>

    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" name="mot_de_passe" required>

    <button type="submit">Se connecter</button>
</form>
</section>
<?php include "includes/footer.php"; ?>
