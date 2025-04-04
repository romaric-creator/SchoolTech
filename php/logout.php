<?php 
    // cide pour la deconnection de l'utilisateur
    session_start();
    session_destroy();
    header("Location: login.php");
    exit();
?>