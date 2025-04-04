<?php
include "config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $id = htmlspecialchars(trim($_POST['hid']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $numero = htmlspecialchars(trim($_POST['numero']));

    $sql = "UPDATE users SET nom = '$nom', email = '$email', numero = '$numero' WHERE id_users = '$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Informations mises à jour avec succès.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la mise à jour des informations.";
    }
    header("Location: ../../php/users.php");
    exit();
} else {
    $_SESSION['error_message'] = "Requête invalide.";
    header("Location: ../../php/users.php");
    exit();
}
?>