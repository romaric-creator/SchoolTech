<?php
include "config.php"; // Inclure la configuration de la base de données

if (isset($_POST['send'])) {
    // Récupération des données du formulaire
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $number = trim($_POST['number']);
    $pp_name = $_FILES['pp']['name'];
    $pp_tmpname = $_FILES['pp']['tmp_name'];

    // Vérification des champs vides
    if (empty($nom) || empty($email) || empty($password) || empty($number) || empty($pp_name)) {
        $error = "Tous les champs sont obligatoires.";
        header("Location: ../../php/install.php?error=" . urlencode($error) . "#t3");
        exit;
    }

    // Sécurisation des données
    $nom = htmlspecialchars($nom);
    $email = htmlspecialchars($email);
    $password = password_hash($password, PASSWORD_BCRYPT); // Hachage du mot de passe
    $number = htmlspecialchars($number);

    // Déplacement du fichier uploadé
    $upload_dir = "../../Images/pp_users/";
    $upload_path = $upload_dir . basename($pp_name);

    if (move_uploaded_file($pp_tmpname, $upload_path)) {
        // Insertion des données dans la base de données
        $sql = "INSERT INTO users (nom, email, pass, numero, status, pp) 
                VALUES ('$nom', '$email', '$password', '$number', 'admin', '$pp_name')";
        $res = mysqli_query($conn, $sql);

        if ($res) {
            // Redirection vers la page d'accueil après succès
            header("Location: ../../php/home.php");
            exit;
        } else {
            $error = "Erreur lors de l'enregistrement.";
            header("Location: ../../php/install.php?error=" . urlencode($error) . "#t3");
            exit;
        }
    } else {
        $error = "Erreur lors du téléchargement de la photo de profil.";
        header("Location: ../../php/install.php?error=" . urlencode($error) . "#t3");
        exit;
    }
}
?>