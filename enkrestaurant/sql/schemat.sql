CREATE DATABASE enk_db;
USE enk_db;

-- Clients
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL
);

-- Administrateurs
CREATE TABLE administrateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'superadmin') DEFAULT 'admin'
);

-- Menu (plats & boissons)
CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    categorie ENUM('plat', 'boisson', 'dessert') DEFAULT 'plat'
);

-- Réservations
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    date_reservation DATE NOT NULL,
    heure TIME NOT NULL,
    nb_personnes INT NOT NULL,
    commentaire TEXT,
    statut ENUM('en attente', 'acceptée', 'refusée') DEFAULT 'en attente',
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- Commandes (liées à une réservation)
CREATE TABLE commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    reservation_id INT,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    statut ENUM('en attente', 'acceptée', 'refusée') DEFAULT 'en attente',
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (reservation_id) REFERENCES reservations(id)
);

-- Détails de chaque plat/boisson commandé
CREATE TABLE commande_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT,
    menu_id INT,
    quantite INT NOT NULL,
    sous_total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commandes(id),
    FOREIGN KEY (menu_id) REFERENCES menu(id)
);
