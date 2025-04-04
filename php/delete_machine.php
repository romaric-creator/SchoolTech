<?php
include '../Asset/traitement/config.php';

if (isset($_POST['idsu'])) {
    $id = intval($_POST['id']);
    $ids = intval($_POST['idsalle']);
    $sql = "DELETE FROM ordinateurs WHERE id_ordinateur = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: list_machine.php?msg=deleted&id_salle=$ids");
    } else {
        echo "Erreur lors de la suppression : " . mysqli_error($conn);
    }
} else {
    header("Location: list_machine.php");
}
?>