<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_POST['send'])) {
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    }
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }
    if ($email == "" || $password == "") {
        $error = "renseignez tous les champs";
        header("Location: ../../php/login.php?error=$error");
        exit();
    } else {
        function securate($data){
            $data = htmlspecialchars(trim($data));
            return $data;
        }
        $email = securate($email);
        $password = securate($password);

        // Récupération de l'utilisateur par email
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($conn, $sql);
        if (mysqli_num_rows($res) > 0) {
            $rows = mysqli_fetch_assoc($res);
            // Vérifier le mot de passe avec password_verify
            if (password_verify($password, $rows['pass'])) {
                $_SESSION['id_users'] = $rows['id_users'];
                header("location: ../../php/home.php");
                exit();
            } else {
                $error = "aucune correspondance";
                header("Location: ../../php/login.php?error=$error");
                exit();
            }
        } else {
            $error = "aucune correspondance";
            header("Location: ../../php/login.php?error=$error");
            exit();
        }
    }
}
?>