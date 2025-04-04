<?php 
session_start();
    $_SESSION['id_users'] = $_GET['set_id'];
    header("Location: ../../php/users.php");

?>