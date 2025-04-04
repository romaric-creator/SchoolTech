<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Css/install.css">
    <link rel="stylesheet" href="../Outils Html/icomoon/style.css">
    <title>Installation - My Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }

        .install {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo img {
            max-width: 150px;
            border-radius: 8px;
        }

        .text-content {
            flex: 1;
        }

        .text-content h1 {
            font-size: 2rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .text-content p {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }

        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .navigation a {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            transition: background 0.3s, transform 0.3s;
        }

        .navigation .cancel a {
            background: #dc3545;
            color: #fff;
        }

        .navigation .cancel a:hover {
            background: #b02a37;
            transform: scale(1.05);
        }

        .navigation .next a {
            background: #28a745;
            color: #fff;
        }

        .navigation .next a:hover {
            background: #218838;
            transform: scale(1.05);
        }

        .message {
            font-size: 1rem;
            margin-top: 10px;
        }

        .success {
            color: #28a745;
        }

        .error {
            color: #dc3545;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .direction {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .direction input[type="reset"],
        .direction input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .direction input[type="reset"] {
            background-color: #dc3545;
            color: white;
        }

        .direction input[type="submit"] {
            background-color: #28a745;
            color: white;
        }

        @media (max-width: 600px) {
            .content {
                flex-direction: column;
                align-items: flex-start;
            }

            .text-content h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body  class="bg-gray-100 font-sans">
    <div class="install">
        <!-- Étape 1 -->
        <div class="step active" id="t1">
            <div class="content">
                <div class="logo">
                    <img src="../Images/IA.png" alt="Logo">
                </div>
                <div class="text-content">
                    <h1>Bienvenue sur My Dashboard</h1>
                    <p>
                        My Dashboard est une application de gestion des salles informatiques conçue pour le Collège
                        Évangélique de New Bell. Elle facilite la réservation, l'utilisation et la maintenance des
                        ordinateurs présents dans les différentes salles.
                    </p>
                </div>
            </div>
            <div class="navigation">
                <div class="cancel">
                    <a href="#" onclick="window.close()">Annuler</a>
                </div>
                <div class="next">
                    <a href="#t2" onclick="showStep('t2')">Suivant</a>
                </div>
            </div>
        </div>

        <!-- Étape 2 -->
        <div class="step" id="t2">
            <div class="content">
                <div class="logo">
                    <img src="../Images/IA.png" alt="Logo">
                </div>
                <div class="text-content">
                    <h1>Installation de la base de données</h1>
                    <p>
                        Cliquez sur le bouton ci-dessous pour commencer l'installation de la base de données. Cela
                        créera toutes les tables nécessaires pour le bon fonctionnement de l'application.
                    </p>
                    <a href="install.php?Go#t2" class="next">Lancer l'installation</a>

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
                                         created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
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
                                         created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
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
                                    if (mysqli_query($conn, $sql)) {
                                        echo '<p class="success">Table créée avec succès.</p>';
                                    } else {
                                        echo '<p class="error">Erreur lors de la création de la table : ' . mysqli_error($conn) . '</p>';
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
                                    mysqli_query($conn, $sql);
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
                </div>
            </div>
            <div class="navigation">
                <div class="cancel">
                    <a href="#t1" onclick="showStep('t1')">Précédent</a>
                </div>
                <div class="next">
                    <a href="#t3" onclick="showStep('t3')">Suivant</a>
                </div>
            </div>
        </div>

        <!-- Étape 3 -->
        <div class="step" id="t3">
            <div class="content">
                <div class="logo">
                    <img src="../Images/IA.png" alt="Logo">
                </div>
                <div class="text-content">
                    <h1>Créer un compte administrateur</h1>
                    <form action="../Asset/traitement/register.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" id="nom" placeholder="Entrez votre nom" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Entrez votre email" required>
                        </div>
                        <div class="form-group">
                            <label for="number">Numéro de téléphone</label>
                            <input type="tel" name="number" id="number" placeholder="Entrez votre numéro de téléphone"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="pp">Photo de profil</label>
                            <input type="file" name="pp" id="pp">
                        </div>
                        <div class="direction">
                            <input type="reset" value="Annuler">
                            <input type="submit" name="send" value="S'enregistrer">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showStep(stepId) {
            const steps = document.querySelectorAll('.step'); // Sélectionne toutes les étapes
            steps.forEach(step => step.classList.remove('active')); // Cache toutes les étapes
            document.getElementById(stepId).classList.add('active'); // Affiche l'étape sélectionnée
        }
    </script>
</body>

</html>