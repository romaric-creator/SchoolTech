<?php 

    $host="localhost";
    $user="root";
    $password="";
    $base="my dashboard";

    $conn=mysqli_connect($host,$user,$password,$base);

    if(!$conn){
        echo "erreur lors de la connexion";
    }
?>