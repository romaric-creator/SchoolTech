<?php
include "config.php";
    if($_POST['send']){
        if($_POST['nom']){
            $nom = $_POST['nom'];
        }
        if($_POST['ra']){
            $ram = $_POST['ra'];
        }
        if($_POST['se']){
            $se = $_POST['se'];
        }
        if($_POST['dd']){
            $dd = $_POST['dd'];
        }
        if($_POST['pr']){
            $pr = $_POST['pr'];
        }
        if($_POST['date']){
            $date = $_POST['date'];
        }
        echo $nom.' '.$ram.' '.$dd.' '.$pr.' '.$se;
        if ($nom == "" || $se == "" || $dd == "" || $pr == "" || $ram == "") {
            $error = "veuillez renseigner tous les champs";
            header("Location: ../../php/stock.php?error=$error#addsalle");
         } else{
           $sql8 = "INSERT INTO stock(nom_sordi,Systeme_E,proces,Disque,ram,date_ajout) VALUES ('$nom','$se','$pr','$dd','$ram','$date')" ;
            $req8 = mysqli_query($conn,$sql8);
             if($req8){
                 header("Location: ../../php/stock.php");
             }else{
                 $error = "erreur lors de l´enregistrement";
                 header("Location: ../../php/stock.php?error=$error#addsalle");   
             }
         }
    }
        
    
?>