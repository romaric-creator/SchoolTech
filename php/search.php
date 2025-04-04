<?php
    if(isset($_GET['search'])){
        if(empty($_GET['search'])){
            header("Location: ../header.php");
        }
    }

    // Handle success and error messages
    if (isset($_SESSION['success_message'])) {
        echo "<div class='alert alert-success' role='alert'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
        unset($_SESSION['success_message']);
    }

    if (isset($_SESSION['error_message'])) {
        echo "<div class='alert alert-danger' role='alert'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
        unset($_SESSION['error_message']);
    }
?>