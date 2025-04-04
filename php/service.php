<?php
$host = "localhost";
$user = "root";
$password = "";
$base = "my dashboard";

$conn = mysqli_connect($host, $user, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sqls = mysqli_select_db($conn, $base);
if (!$sqls) {
    header("Location: install.php#t1");
    exit();
}

include '../Asset/traitement/login.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: login.php");
    exit();
}

$id_p = $_SESSION['id_users'];

// Requête pour récupérer l'image de profil de l'utilisateur
$sql_user = "SELECT * FROM users WHERE id_users = '$id_p'";
$res_user = mysqli_query($conn, $sql_user);
$row_user = mysqli_fetch_assoc($res_user);
$profile_image = !empty($row_user['pp']) ? $row_user['pp'] : 'default.png';

// Ajout : vérifier et marquer un service comme "effectuer"
if (isset($_GET['action']) && $_GET['action'] === 'effectuer' && isset($_GET['id'])) {
    $id_service = intval($_GET['id']);
    $update_sql = "UPDATE service SET action = 'effectuer' WHERE id_service = $id_service";
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['success'] = "Service marqué comme effectué avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour du service.";
    }
    header("Location: service.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="../css/style.css">
    <title>Services</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }

        .header {
            background-color: #007bff;
            color: #fff;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header .logo img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .header .logo p {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .header .menu_us {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header .menu_us .icon-bell {
            font-size: 1.5rem;
            position: relative;
            cursor: pointer;
            color: #fff;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .header .menu_us .icon-bell:hover {
            transform: scale(1.2);
            color: #ffc107;
        }

        .header .menu_us .icon-bell .num {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #dc3545;
            color: #fff;
            font-size: 0.8rem;
            padding: 2px 6px;
            border-radius: 50%;
        }

        .header .menu_us img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fff;
            object-fit: cover;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        .list_mach {
            padding: 20px;
        }

        .mach2 {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .mach2 .up {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .mach2 table {
            width: 100%;
            border-collapse: collapse;
        }

        .mach2 table td {
            padding: 10px;
            font-size: 1rem;
            color: #555;
        }

        .mach2 table td:first-child {
            font-weight: bold;
            color: #333;
        }

        .boop {
            text-align: center;
            margin: 20px 0;
        }

        .boop .right {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .boop .right:hover {
            background-color: #218838;
        }


        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 500px;
            max-width: 90%;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            color: #007bff;
        }

        .modal-header .close {
            font-size: 1.5rem;
            color: #dc3545;
            cursor: pointer;
        }

        .modal-footer {
            text-align: right;
            margin-top: 20px;
        }

        .modal-footer a {
            text-decoration: none;
            color: #dc3545;
            margin-right: 20px;
        }

        .modal-footer input {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-footer input:hover {
            background-color: #0056b3;
        }

        form {
            font-size: 1rem;
            color: #333;
        }

        form table {
            width: 100%;
            margin-bottom: 20px;
        }

        form table td {
            padding: 10px;
            vertical-align: top;
        }

        form table td label {
            font-weight: bold;
            color: #007bff;
        }

        form table td input,
        form table td textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            color: #333;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        form table td input:focus,
        form table td textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        form table td textarea {
            resize: none;
            height: 100px;
        }

        .modal-footer input {
            padding: 12px 25px;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .modal-footer a {
            font-size: 1.1rem;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <?php
    $title = "services";
    include '../Asset/traitement/sidebar.php';
    ?>
    <!-- Header -->
    <div class="header bg-primary shadow-md md:ml-64 transition-all">
        <div></div>
        <div class="menu_us">
            <a href="notification.php?vue" class="icon-bell">
                <i class="fas fa-bell"></i>
                <?php
                // Notifications des réservations en attente
                $sqlnot = "SELECT * FROM reservation WHERE status = 'on'";
                $resnot = mysqli_query($conn, $sqlnot);
                $numnot = $resnot ? mysqli_num_rows($resnot) : 0;
                // Notifications des services nouvellement ajoutés
                $sqlservnot = "SELECT * FROM service WHERE status = 'nouveau'";
                $resservnot = mysqli_query($conn, $sqlservnot);
                $numnot += $resservnot ? mysqli_num_rows($resservnot) : 0;
                if ($numnot > 0) {
                    echo '<span class="num">' . $numnot . '</span>';
                }
                ?>
            </a>
            <img src="<?php echo '../images/pp_users/' . $profile_image ?>" alt="User Profile">
        </div>
    </div>



    <!-- Main content -->
    <div class="main-content">
        <div class="boop">
            <a href="#" class="right" id="openModal"><i class="fas fa-plus"></i> Ajouter un service</a>
        </div>
        <div class="list_mach">
            <?php
            // Affichage des messages de succès ou d'erreur
            if (isset($_SESSION['success'])) {
                echo '<p style="color: green; font-weight: bold;">' . $_SESSION['success'] . '</p>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo '<p style="color: red; font-weight: bold;">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }

            $sqlmach = "SELECT * FROM service";
            $reqmach = mysqli_query($conn, $sqlmach);
            $numac = mysqli_num_rows($reqmach);
            if ($numac == 0) {
                echo '<p class="nmac"><i class="fas fa-info-circle"></i> Aucun service disponible</p>';
            } else {
                while ($rowmach = mysqli_fetch_assoc($reqmach)) {
                    echo '
                    <div class="mach2">
                        <p class="up"><i class="fas fa-user"></i> Demande de service effectuée par <strong>Mr ' . htmlspecialchars($rowmach['nom_us']) . '</strong></p>
                        <table>
                            <tr><td><i class="fas fa-phone"></i> Téléphone :</td><td>' . htmlspecialchars($rowmach['tel']) . '</td></tr>
                            <tr><td><i class="fas fa-comment"></i> Description :</td><td>' . htmlspecialchars($rowmach['contenu']) . '</td></tr>
                        </table>
                        ';

                    // Si le service n'est pas marqué comme effecteur (ou statut vide), afficher le lien d'action
                    if ($row_user['status'] == 'admin') {
                        if (empty($rowmach['action']) || $rowmach['action'] !== 'effectuer') {
                            echo '<a href="service.php?action=effectuer&id=' . $rowmach['id_service'] . '" style="display:inline-block;margin-top:10px;color:#28a745;font-weight:bold;"><i class="fas fa-check"></i> Marquer comme effecteur</a>';
                        } else {
                            echo '<p style="margin-top:10px;color:#6c757d;"><i class="fas fa-check-circle"></i> Service effectué</p>';
                        }
                        echo '
                    </div>';
                    }
                }
            }
            ?>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="serviceModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-plus-circle"></i> Ajouter un service</h2>
                <span class="close" id="closeModal">&times;</span>
            </div>
            <form action="../Asset/traitement/service.php" method="post">
                <!-- Ajout de gestion de succès/échec pour l'ajout de service -->
                <?php
                if (isset($_SESSION['success'])) {
                    echo '<p style="color: green; font-weight: bold;">' . $_SESSION['success'] . '</p>';
                    unset($_SESSION['success']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<p style="color: red; font-weight: bold;">' . $_SESSION['error'] . '</p>';
                    unset($_SESSION['error']);
                }
                ?>
                <table>
                    <tr>
                        <td><label for="nom"><i class="fas fa-user"></i> Nom utilisateur</label></td>
                        <td><input type="text" name="nom" id="nom" class="it it2"></td>
                    </tr>
                    <tr>
                        <td><label for="tel"><i class="fas fa-phone"></i> Téléphone</label></td>
                        <td><input type="text" name="tel" id="tel" class="it it2"></td>
                    </tr>
                    <tr>
                        <td><label for="co"><i class="fas fa-comment"></i> Description</label></td>
                        <td><textarea class="it it2" id="co" name="contenu"></textarea></td>
                    </tr>
                </table>
                <div class="modal-footer">
                    <a href="#" id="cancelModal"><i class="fas fa-times-circle"></i> Annuler</a>
                    <input type="submit" value="Soumettre" name="send">
                </div>
            </form>
        </div>
    </div>

    <script>
        const openModal = document.getElementById('openModal');
        const closeModal = document.getElementById('closeModal');
        const cancelModal = document.getElementById('cancelModal');
        const modal = document.getElementById('serviceModal');

        openModal.addEventListener('click', (e) => {
            e.preventDefault();
            modal.style.display = 'flex';
        });

        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        cancelModal.addEventListener('click', (e) => {
            e.preventDefault();
            modal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
</body>

</html>