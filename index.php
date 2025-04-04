<?php
$host = "localhost";
$user = "root";
$password = "";
$base = "my dashboard";

$conn = mysqli_connect($host, $user, $password);
$sqls = mysqli_select_db($conn, $base);
if ($sqls) {
    header("Location: php/home.php");
} else {
    echo '<script>window.open("php/install.php#t1", "_blank");</script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
    <title>Accueil</title>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-10">
        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-gray-700">
                des salles d'informatique conçues pour le collège évangélique de New Bell dans le but de gérer la réservation et faciliter l'utilisation et la maintenance des ordinateurs présents dans les différentes salles.
            </p>
        </div>
        <div class="flex justify-between items-center mt-6">
            <div></div>
            <div>
                <a href="index.php" id="right" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Installer</a>
            </div>
        </div>
    </div>
</body>

</html>