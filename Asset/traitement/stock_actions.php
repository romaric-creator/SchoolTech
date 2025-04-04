<?php
include "config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'modify') {
            $id = htmlspecialchars(trim($_POST['id_stock']));
            $nom = htmlspecialchars(trim($_POST['nom_sordi']));
            $se = htmlspecialchars(trim($_POST['Systeme_E']));
            $ram = (int)$_POST['ram'];
            $disque = (int)$_POST['Disque'];
            $proces = htmlspecialchars(trim($_POST['proces']));
            $date = htmlspecialchars(trim($_POST['date_ajout']));

            $sql = "UPDATE stock SET nom_sordi = '$nom', Systeme_E = '$se', ram = $ram, Disque = $disque, proces = '$proces', date_ajout = '$date' WHERE id_stock = '$id'";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['success_message'] = "Machine mise à jour avec succès.";
            } else {
                $_SESSION['error_message'] = "Erreur lors de la mise à jour de la machine.";
            }
        } elseif ($action === 'delete') {
            $id = htmlspecialchars(trim($_POST['id']));
            $sql = "DELETE FROM stock WHERE id_stock = '$id'";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['success_message'] = "Machine supprimée avec succès.";
            } else {
                $_SESSION['error_message'] = "Erreur lors de la suppression de la machine.";
            }
        }
    }
    header("Location: ../../php/stock.php");
    exit();
} else {
    $_SESSION['error_message'] = "Requête invalide.";
    header("Location: ../../php/stock.php");
    exit();
}
?>
