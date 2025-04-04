<?php
include "config.php";
session_start();

$host = "localhost";
$user = "root";
$password = "";
$base = "my dashboard";
$conn = mysqli_connect($host, $user, $password, $base);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    if (!empty($_POST['nom']) && !empty($_POST['tel']) && !empty($_POST['contenu'])) {
        $nom = htmlspecialchars(trim($_POST['nom']));
        $tel = htmlspecialchars(trim($_POST['tel']));
        $contenu = htmlspecialchars(trim($_POST['contenu']));

        $sql = "INSERT INTO service (nom_us, tel, contenu, status) VALUES ('$nom', '$tel', '$contenu', 'nouveau')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success_message'] = "Service ajouté avec succès.";
        } else {
            $_SESSION['error_message'] = "Erreur lors de l'ajout du service.";
        }
    } else {
        $_SESSION['error_message'] = "Veuillez renseigner tous les champs.";
    }
    header("Location: ../../php/service.php");
    exit();
} else {
    $_SESSION['error_message'] = "Requête invalide.";
    header("Location: ../../php/service.php");
    exit();
}
?>