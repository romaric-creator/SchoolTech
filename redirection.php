<?php
 include 'Asset/traitement/login.php';
 echo $_SESSION['id_users'];
    if (isset($_SESSION['id_users'])) {
        header("Location: php/home.php");
    }else{
        header("Location: php/login.php");
    }
?>