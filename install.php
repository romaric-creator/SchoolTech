<?php
ob_start(); // Active la mise en tampon de sortie

if (isset($_GET['Go'])) {
    $host = "localhost";
    $user = "root";
    $password = "";
    $base = "my dashboard";

    $conn = mysqli_connect($host, $user, $password);
    if (!$conn) {
        echo '<p class="error">Erreur de connexion au serveur MySQL : ' . mysqli_connect_error() . '</p>';
    } else {
        // Supprimer la base de données existante si elle existe
        $sqldel = "DROP DATABASE IF EXISTS `$base`";
        mysqli_query($conn, $sqldel);

        // Créer la base de données
        $sqlba = "CREATE DATABASE `$base`";
        $res = mysqli_query($conn, $sqlba);
        if ($res) {
            $conn = mysqli_connect($host, $user, $password, $base);
            echo '<p class="success">Base de données créée avec succès.</p>';

            // Création des tables
            $tables = [
                "CREATE TABLE `ordinateurs` (
                    `id_ordinateur` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `id_salle` int(11) NOT NULL,
                    `nom_ordi` varchar(255) NOT NULL,
                    `Systeme_E` varchar(255) NOT NULL,
                    `proces` varchar(255) NOT NULL,
                    `Disque` int(11) NOT NULL,
                    `ranger` int(11) NOT NULL DEFAULT 1,
                    `ram` int(11) NOT NULL,
                    `date_maint` Date DEFAULT CURRENT_DATE
                )",
                "CREATE TABLE `reservation` (
                    `id_reservation` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `nom_us` varchar(100) NOT NULL,
                    `date_res` date NOT NULL,
                    `nom_salle` varchar(100) NOT NULL,
                    `tel` int(11) NOT NULL,
                    `debh` varchar(100) NOT NULL,
                    `debf` varchar(100) NOT NULL,
                    `status` varchar(3) NOT NULL
                )",
                "CREATE TABLE `salle` (
                    `id_salle` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `nom_salle` varchar(100) NOT NULL,
                    `capacite` int(11) NOT NULL,
                    `disponibilite` varchar(100) NOT NULL
                )",
                "CREATE TABLE `service` (
                    `id_service` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `nom_us` varchar(100) NOT NULL,
                    `tel` int(11) NOT NULL,
                    date_service DATE DEFAULT CURRENT_DATE,
                    `contenu` varchar(200) NOT NULL,
                    `status` varchar(20) NOT NULL DEFAULT '',
                    `action` varchar(20) NOT NULL DEFAULT ''
                )",
                "CREATE TABLE `stock` (
                    `id_stock` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `nom_sordi` varchar(200) NOT NULL,
                    `Systeme_E` varchar(200) NOT NULL,
                    `proces` varchar(200) NOT NULL,
                    `Disque` int(11) NOT NULL,
                    `ram` int(11) NOT NULL,
                    `date_ajout` date NOT NULL
                )",
                "CREATE TABLE `users` (
                    `id_users` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `nom` varchar(115) NOT NULL,
                    `email` varchar(115) NOT NULL,
                    `pass` varchar(115) NOT NULL,
                    `numero` int(11) NOT NULL,
                    `status` varchar(110) NOT NULL,
                    `pp` varchar(115) NOT NULL
                )"
            ];

            foreach ($tables as $sql) {
                if (!mysqli_query($conn, $sql)) {
                    echo '<p class="error">Erreur lors de la création de la table : ' . mysqli_error($conn) . '</p>';
                    exit(); // Arrête le processus si une table échoue
                } else {
                    echo '<p class="success">Table créée avec succès.</p>';
                }
            }

            // Insertion des données initiales
            $sqlInsert = [
                "INSERT INTO salle (id_salle, nom_salle, capacite, disponibilite) VALUES
                (1, 'salle 1', 0, 'disponible'),
                (2, 'salle 2', 0, 'disponible'),
                (3, 'salle 3', 0, 'disponible')"
            ];

            foreach ($sqlInsert as $sql) {
                if (!mysqli_query($conn, $sql)) {
                    echo '<p class="error">Erreur lors de l\'insertion des données initiales : ' . mysqli_error($conn) . '</p>';
                    exit(); // Arrête le processus si l'insertion échoue
                }
            }

            echo '<p class="success">Données initiales insérées avec succès.</p>';

            // Redirection vers l'étape 3
            echo '<script>window.location.href = "install.php#t3";</script>';
        } else {
            echo '<p class="error">Erreur lors de la création de la base de données.</p>';
        }
    }
}

ob_end_flush(); // Envoie les données tamponnées au navigateur
?>
