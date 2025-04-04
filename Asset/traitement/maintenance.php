<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    if (!empty($_POST['nomsalle']) && !empty($_POST['disponibilite'])) {
        $nomsalle = trim($_POST['nomsalle']);
        $disponibilite = $_POST['disponibilite'];

        $sql = "INSERT INTO salle (nom_salle, disponibilite) VALUES ('$nomsalle', '$disponibilite')";
        $req = mysqli_query($conn, $sql);

        if ($req) {
            header("Location: ../../php/maintenance.php?success=La salle a été créée avec succès");
            exit();
        } else {
            $error = "Erreur lors de l'enregistrement";
            header("Location: ../../php/maintenance.php?error=$error#salle");
            exit();
        }
    } else {
        $error = "Veuillez renseigner tous les champs";
        header("Location: ../../php/maintenance.php?error=$error#salle");
        exit();
    }
} else {
    header("Location: ../../php/maintenance.php");
    exit();
}
?>