<?php
require_once 'connexion.php';

// ✅ Insertion des plats
$pdo->exec("INSERT INTO plats (nom, description, prix, disponible) VALUES
    ('Pizza Margherita', 'Sauce tomate, mozzarella, basilic', 9.50, 1),
    ('Burger Maison', 'Steak, fromage, salade, sauce maison', 11.90, 1),
    ('Salade César', 'Poulet, parmesan, croutons, sauce césar', 8.90, 1)
");

// ✅ Insertion des réservations
$pdo->exec("INSERT INTO reservations (nom_client, date_reservation, heure, nb_personnes) VALUES
    ('Alice Dupont', '2025-04-05', '19:30', 2),
    ('Mohamed El Kharraz', '2025-04-06', '20:00', 4)
");

// ✅ Insertion des commandes
$pdo->exec("INSERT INTO commandes (nom_client, date_commande, total, statut) VALUES
    ('Alice Dupont', '2025-04-05', 25.80, 'en préparation'),
    ('Mohamed El Kharraz', '2025-04-06', 42.30, 'prête')
");

echo "✅ Données de test insérées avec succès !";
?>
