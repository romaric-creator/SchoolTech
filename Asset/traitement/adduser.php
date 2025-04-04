<?php
include "config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $role = htmlspecialchars(trim($_POST['role']));

    $sql = "INSERT INTO users (nom, email, pass, status) VALUES ('$name', '$email', '$password', '$role')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Utilisateur ajouté avec succès.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de l'ajout de l'utilisateur.";
    }
    header("Location: ../../php/users.php");
    exit();
} else {
    $_SESSION['error_message'] = "Requête invalide.";
    header("Location: ../../php/users.php");
    exit();
}
?>
