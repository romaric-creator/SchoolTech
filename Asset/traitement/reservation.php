<?php
include "config.php";

if (isset($_POST['send'])) {
    $id_reservation = isset($_POST['id_reservation']) && !empty($_POST['id_reservation']) ? $_POST['id_reservation'] : null;
    $nom = trim($_POST['nom']);
    $numero = trim($_POST['numero']);
    $date = $_POST['date'];
    $heuredeb = $_POST['heuredeb'];
    $heurefin = $_POST['heurefin'];
    $salle = trim($_POST['salle']);

    // Vérification des champs vides
    if (empty($nom) || empty($numero) || empty($date) || empty($heuredeb) || empty($heurefin) || empty($salle)) {
        $error = "Tous les champs sont obligatoires. Veuillez les remplir.";
        header("Location: ../../php/reservation.php?error=$error#reservation");
        exit();
    }

    // Vérification des heures
    if ($heuredeb >= $heurefin) {
        $error = "L'heure de début doit être inférieure à l'heure de fin.";
        header("Location: ../../php/reservation.php?error=$error#reservation");
        exit();
    }

    // Vérification des conflits de réservation
    $sql_check = "SELECT * FROM reservation 
                  WHERE nom_salle = '$salle' 
                  AND date_res = '$date' 
                  AND (
                      ('$heuredeb' BETWEEN debh AND debf) OR 
                      ('$heurefin' BETWEEN debh AND debf) OR 
                      (debh BETWEEN '$heuredeb' AND '$heurefin') OR 
                      (debf BETWEEN '$heuredeb' AND '$heurefin')
                  )";
    if ($id_reservation) {
        // Exclure la réservation actuelle lors de la modification
        $sql_check .= " AND id_reservation != '$id_reservation'";
    }
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        $error = "Conflit de réservation : une autre réservation existe pour cette salle à ces heures.";
        header("Location: ../../php/reservation.php?error=$error#reservation");
        exit();
    }

    // Ajout ou modification de la réservation
    if ($id_reservation) {
        // Modification
        $sql = "UPDATE reservation 
                SET nom_us = '$nom', date_res = '$date', nom_salle = '$salle', tel = '$numero', debh = '$heuredeb', debf = '$heurefin' 
                WHERE id_reservation = '$id_reservation'";
    } else {
        // Ajout
        $sql = "INSERT INTO reservation (nom_us, date_res, nom_salle, tel, debh, debf, status) 
                VALUES ('$nom', '$date', '$salle', '$numero', '$heuredeb', '$heurefin', 'on')";
    }

    $req = mysqli_query($conn, $sql);
    if ($req) {
        $success = $id_reservation ? "Réservation modifiée avec succès." : "Réservation ajoutée avec succès.";
        header("Location: ../../php/reservation.php?success=$success");
    } else {
        $error = "Erreur lors de l'enregistrement. Veuillez réessayer.";
        header("Location: ../../php/reservation.php?error=$error#reservation");
    }
    exit();
}

if (isset($_POST['delete'])) {
    $id_reservation = $_POST['id_reservation'];
    if (!empty($id_reservation)) {
        $sql = "DELETE FROM reservation WHERE id_reservation = '$id_reservation'";
        $req = mysqli_query($conn, $sql);
        if ($req) {
            header("Location: ../../php/reservation.php?success=Réservation supprimée avec succès.");
        } else {
            $error = "Erreur lors de la suppression. Veuillez réessayer.";
            header("Location: ../../php/reservation.php?error=$error#reservation");
        }
    } else {
        $error = "ID de réservation manquant pour la suppression.";
        header("Location: ../../php/reservation.php?error=$error#reservation");
    }
    exit();
}
?>