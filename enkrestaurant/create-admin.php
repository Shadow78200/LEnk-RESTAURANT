<?php
// create-admin.php
require_once "includes/db.php";

// 🔒 Protection basique
if ($_GET['secret'] !== 'create') {
    die("Accès refusé");
}

$nom = "Admin";
$email = "admin@enk.fr";
$mot_de_passe = password_hash("admin123", PASSWORD_DEFAULT);
$role = "superadmin";

$stmt = $pdo->prepare("INSERT INTO administrateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$nom, $email, $mot_de_passe, $role]);

echo "✅ Compte admin créé avec succès.";


// pour créer l'admin 

// http://localhost:8888/create-admin.php?secret=create
