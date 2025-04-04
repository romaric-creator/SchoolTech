<?php  
include 'config.php';
    if($_POST['mod']){
        if($_POST['nom']){
            $nom = $_POST['nom'];
        }
        if($_POST['numero']){
            $numero = $_POST['numero'];
        }
        if($_POST['date']){
            $date = $_POST['date'];
        }
        if($_POST['heuredeb']){
            $heuredeb = $_POST['heuredeb'];
        }
        if($_POST['heurefin']){
            $heurefin = $_POST['heurefin'];
        }
        if($_POST['salle']){
            $salle = $_POST['salle'];
        }
        if($_GET['id_Re']){
            $id = $_GET['id_Re'];
        }
        if ($nom == "" || $numero == "" || $date == "" || $heuredeb == "" || $heurefin == "") {
            $error = "veuillez renseigner tous les champs";
            header("Location: ../../php/reservation.php?error=$error#reservation");
        }else{
            if(!empty($nom)){
                $sql = "UPDATE reservation SET nom_us = '$nom' WHERE id_reservation ='$id' ";
                $req = mysqli_query($conn,$sql);
                if($req){
                    header("Location: ../../php/reservation.php");
                }else{
                    $error = "erreur lors de l´enregistrement";
                    header("Location: ../../php/reservation.php?error=$error#reservation");   
                }
            }
            if(!empty($date)){
                $sql2 = "UPDATE reservation SET date_res = '$date' WHERE id_reservation ='$id' ";
                $req2 = mysqli_query($conn,$sql2);
                if($req2){
                    header("Location: ../../php/reservation.php");
                }else{
                    $error = "erreur lors de l´enregistrement";
                    header("Location: ../../php/reservation.php?error=$error#reservation");   
                }
            }
            if(!empty($numero)){
                $sql3 = "UPDATE reservation SET tel = '$numero' WHERE id_reservation ='$id' ";
                $req3 = mysqli_query($conn,$sql3);
                if($req3){
                    header("Location: ../../php/reservation.php");
                }else{
                    $error = "erreur lors de l´enregistrement";
                    header("Location: ../../php/reservation.php?error=$error#reservation");   
                }
            }
            if(!empty($salle)){
                $sql4 = "UPDATE reservation SET nom_salle = '$salle' WHERE id_reservation ='$id' ";
                $req4 = mysqli_query($conn,$sql4);
                if($req4){
                    header("Location: ../../php/reservation.php");
                }else{
                    $error = "erreur lors de l´enregistrement";
                    header("Location: ../../php/reservation.php?error=$error#reservation");   
                }
            }
            if(!empty($heuredeb)){
                $sql5 = "UPDATE reservation SET debh = '$heuredeb' WHERE id_reservation ='$id' ";
                $req5 = mysqli_query($conn,$sql5);
                if($req5){
                    header("Location: ../../php/reservation.php");
                }else{
                    $error = "erreur lors de l´enregistrement";
                    header("Location: ../../php/reservation.php?error=$error#reservation");   
                }
            }
            if(!empty($heurefin)){
                $sql5 = "UPDATE reservation SET debf = '$heurefin' WHERE id_reservation ='$id' ";
                $req5 = mysqli_query($conn,$sql5);
                if($req5){
                    header("Location: ../../php/reservation.php");
                }else{
                    $error = "erreur lors de l´enregistrement";
                    header("Location: ../../php/reservation.php?error=$error#reservation");   
                }
            }
        }
    }
    if(isset($_GET['sup'])){
        $idsup = $_GET['sup'];
        echo $idsup;
        $sqlsup = "DELETE FROM reservation WHERE id_reservation = '$idsup'";
        $ressup = mysqli_query($conn,$sqlsup);
        if($ressup){
            header("Location: ../../php/reservation.php?ok");   
        }else{
            header("Location: ../../php/reservation.php?no");   
        }

    }
       
?>