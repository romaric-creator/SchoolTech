<?php
include "config.php";
if ($_POST['send']) {
    if ($_POST['nom']) {
        $nom = $_POST['nom'];
    }
    if ($_POST['id']) {
        $id = $_POST['id'];
    }
    if ($_POST['ra']) {
        $ram = $_POST['ra'];
    }
    if ($_POST['se']) {
        $se = $_POST['se'];
    }
    if ($_POST['dd']) {
        $dd = $_POST['dd'];
    }
    if ($_POST['ranger']) {
        $ranger = $_POST['ranger'];
    }
    if ($_POST['pr']) {
        $pr = $_POST['pr'];
    }
    if ($_POST['date']) {
        $date = $_POST['date'];
    }
    if ($nom == "" || $se == "" || $dd == "" || $pr == "" || $ram == "") {
        $error = "veuillez renseigner tous les champs";
        header("Location: ../../php/list_machine.php?id_salle=$id & error=$error#addsalle");
    } else {
        $sql8 = "INSERT INTO ordinateurs (id_salle,nom_ordi,Systeme_E,proces,Disque,ram,ranger) VALUES ('$id','$nom','$se','$pr','$dd','$ram','$ranger')";
        $req8 = mysqli_query($conn, $sql8);
        if ($req8) {
            $_SESSION['success_message'] = "Ordinateur  ajoutée avec succès.";
            header("Location: ../../php/list_machine.php?id_salle=$id");

        } else {
            $_SESSION['error_message'] = "Erreur lors de l'ajout de l'ordinateur    .";
            $error = "erreur lors de l´enregistrement";
            header("Location: ../../php/list_machine.php?id_salle=$id & error=$error#addsalle");
        }
    }
}


?>