<?php 
include "config.php";
session_start(); // Démarrer la session

if (isset($_POST['send'])) {
    // Récupérer les données du formulaire
    $passwordA = isset($_POST['passwordA']) ? $_POST['passwordA'] : '';
    $passwordN = isset($_POST['passwordN']) ? $_POST['passwordN'] : '';
    $id = isset($_POST['hid']) ? intval($_POST['hid']) : 0; // Utiliser intval pour sécuriser l'ID

    // Vérification des champs requis
    if (empty($passwordA) || empty($passwordN)) {
        $_SESSION['error'] = "Veuillez renseigner tous les champs.";
        header("Location: ../../php/users.php#mdp");
        exit();
    }

    // Fonction de sécurisation des données
    function securate($data) {
        return htmlspecialchars(trim($data));
    }

    // Sécuriser les mots de passe
    $passwordA = securate($passwordA);
    $passwordN = securate($passwordN);

    // Récupérer l'utilisateur par ID
    $sql = "SELECT * FROM users WHERE id_users = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $resmdp = mysqli_stmt_get_result($stmt);
    $rowmdp = mysqli_fetch_assoc($resmdp);

    // Vérifier le mot de passe actuel
    if ($rowmdp && password_verify($passwordA, $rowmdp['pass'])) {
        // Vérifier si le nouveau mot de passe est différent de l'ancien
        if ($passwordA === $passwordN) {
            $_SESSION['error'] = "Veuillez utiliser un mot de passe différent.";
            header("Location: ../../php/users.php#mdp");
            exit();
        } else {
            // Mettre à jour le mot de passe
            $hashed_password = password_hash($passwordN, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET pass = ? WHERE id_users = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $hashed_password, $id);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Mot de passe mis à jour avec succès.";
                header("Location: ../../php/users.php#mdp");
                exit();
            } else {
                $_SESSION['error'] = "Erreur lors de l'enregistrement.";
                header("Location: ../../php/users.php#mdp");
                exit();
            }
        }
    } else {
        $_SESSION['error'] = "Mot de passe incorrect.";
        header("Location: ../../php/users.php#mdp");
        exit();
    }
}
?>