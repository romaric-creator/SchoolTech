<?php
$host = "localhost";
$user = "root";
$password = "";
$base = "my dashboard";

$conn = mysqli_connect($host, $user, $password);
$sqls = mysqli_select_db($conn, $base);
if(!$sqls) { header("Location: install.php#t1"); exit(); }
include '../Asset/traitement/login.php';
if(!isset($_SESSION['id_users'])) { header("Location: login.php"); exit(); }

$id = $_GET['id'];
$ids = $_GET['idsalle'];
$sql = "SELECT * FROM ordinateurs WHERE id_ordinateur='$id'";
$result = mysqli_query($conn, $sql);
$machine = mysqli_fetch_assoc($result);
// If form submitted, update machine in ordinateurs table
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_ordinateur'];
    $nom_ordi   = mysqli_real_escape_string($conn, $_POST['nom_ordi']);
    $Systeme_E  = mysqli_real_escape_string($conn, $_POST['Systeme_E']);
    $ram        = (int)$_POST['ram'];
    $Disque     = (int)$_POST['Disque'];
    $ids     = (int)$_POST['ids'];
    $proces     = mysqli_real_escape_string($conn, $_POST['proces']);
    $date_ajout = $_POST['date_ajout'];
    $sqlUpdate = "UPDATE ordinateurs SET 
                    nom_ordi='$nom_ordi',
                    Systeme_E='$Systeme_E',
                    ram=$ram,
                    Disque=$Disque,
                    proces='$proces',
                    date_maint='$date_ajout'
                  WHERE id_ordinateur='$id'";
    if(mysqli_query($conn, $sqlUpdate)){
        $_SESSION['success_message'] = "Machine modifiée avec succès.";
        header('Location: list_machine.php?id_salle='.$ids.'');
        exit();
    } else {
        $_SESSION['error_message'] = "Erreur lors de la modification: " . mysqli_error($conn);
    }
}

// If GET, fetch existing machine data

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Machine</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f6f9; 
            margin: 0; 
            padding: 20px; 
        }
        .container {
            max-width: 600px;
            background: #fff;
            padding: 30px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 { text-align: center; color: #007bff; margin-bottom: 20px; }
        form { display: flex; flex-direction: column; gap: 15px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-weight: bold; margin-bottom: 5px; }
        .form-group input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            width: 100%;
        }
        button {
            padding: 10px;
            background: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
        .error { color: red; margin-bottom: 15px; text-align: center; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { text-decoration: none; color: #007bff; }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 1rem;
        }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-danger { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body  class="bg-gray-100 font-sans">
    <div class="container">
        <h1><i class="fas fa-edit"></i> Modifier la Machine</h1>
        <?php 
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
        <form action="edit_machine.php" method="post">
            <div class="form-group">
                <label for="nom_ordi"><i class="fas fa-laptop"></i> Nom de la machine</label>
                <input type="text" id="nom_ordi" name="nom_ordi" value="<?php echo htmlspecialchars($machine['nom_ordi']); ?>" required>
            </div>
            <div class="form-group">
                <label for="Systeme_E"><i class="fas fa-desktop"></i> Système d'exploitation</label>
                <input type="text" id="Systeme_E" name="Systeme_E" value="<?php echo htmlspecialchars($machine['Systeme_E']); ?>" required>
            </div>
            <div class="form-group">
                <label for="ram"><i class="fas fa-memory"></i> RAM (Go)</label>
                <input type="number" id="ram" name="ram" value="<?php echo htmlspecialchars($machine['ram']); ?>" required>
            </div>
            <div class="form-group">
                <label for="Disque"><i class="fas fa-hdd"></i> Disque dur (Go)</label>
                <input type="number" id="Disque" name="Disque" value="<?php echo htmlspecialchars($machine['Disque']); ?>" required>
            </div>
            <div class="form-group">
                <label for="proces"><i class="fas fa-microchip"></i> Processeur</label>
                <input type="text" id="proces" name="proces" value="<?php echo htmlspecialchars($machine['proces']); ?>" required>
            </div>
            <div class="form-group">
                <label for="date_ajout"><i class="fas fa-calendar-alt"></i> Date d'ajout</label>
                <input type="date" id="date_ajout" name="date_ajout" value="<?php echo htmlspecialchars($machine['date_ajout']); ?>" required>
            </div>
            <input type="hidden" name="id_ordinateur" value="<?php echo $machine['id_ordinateur']; ?>">
            <input type="hidden" name="action" value="modify">
            <input type="hidden" name="ids" value="<?php echo $ids; ?>">
            <button type="submit"><i class="fas fa-save"></i> Enregistrer les modifications</button>
        </form>
        <div class="back-link">
            <a href="machines.php"><i class="fas fa-chevron-left"></i> Retour aux machines</a>
        </div>
    </div>
</body>
</html>