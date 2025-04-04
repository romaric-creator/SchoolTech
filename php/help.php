<?php 
        $host="localhost";
        $user="root";
        $password="";
        $base="my dashboard";
    
        $conn = mysqli_connect($host,$user,$password);
        $sqls = mysqli_select_db($conn,$base);
        if($sqls){
            
        }else{
            header("Location: install.php#t1");
        }
        include '../Asset/traitement/login.php';
        $id_p = $_SESSION['id_users'];
if(isset($_SESSION['id_users'])){?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .boxaide {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            right:0;
            position: absolute;
            top:80px;
            width: 80%;
            padding: 20px;
            justify-content: center;
        }

        .box {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .hea {
            font-size: 1.2rem;
            color: #007bff;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hea i {
            color: #007bff;
        }

        ol {
            padding-left: 20px;
        }

        ol li {
            margin-bottom: 10px;
            font-size: 0.95rem;
            color: #555;
        }

        ol li p {
            margin: 0;
        }

        /* Header styles */
        nav.box-bar {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            right:0;
            position: absolute;
            width: 80%;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        nav.box-bar .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        nav.box-bar .logo img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        nav.box-bar .logo p {
            font-size: 1.2rem;
            font-weight: bold;
            margin: 0;
        }

        nav.box-bar .search_bar {
            flex: 1;
            margin: 0 20px;
        }

        nav.box-bar .search_bar input {
            width: 100%;
            padding: 8px 12px;
            border: none;
            border-radius: 20px;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        nav.box-bar .menu_us {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        nav.box-bar .menu_us .icon-bell {
            position: relative;
            font-size: 1.5rem;
            cursor: pointer;
        }

        nav.box-bar .menu_us .icon-bell .num {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #dc3545;
            color: #fff;
            font-size: 0.8rem;
            font-weight: bold;
            border-radius: 50%;
            padding: 2px 6px;
        }

        nav.box-bar .menu_us img.pp {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        /* Icône de notification */
        .icon-bell {
            position: relative;
            font-size: 1.5rem;
            color: #fff;
            cursor: pointer;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .icon-bell:hover {
            color: #ffc107; /* Couleur jaune au survol */
            transform: scale(1.2); /* Agrandissement au survol */
        }

        .icon-bell .num {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #dc3545; /* Rouge pour les notifications */
            color: #fff;
            font-size: 0.8rem;
            font-weight: bold;
            border-radius: 50%;
            padding: 2px 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .icon-bell .num:empty {
            display: none; /* Cache le badge s'il n'y a pas de notifications */
        }
    </style>
</head>
<body  class="bg-gray-100 font-sans">
    <nav class="box-bar">
        <div class="logo">
        </div>
        <div class="menu_us">
            <?php 
                include "../Asset/traitement/config.php";
                $sqlnot = "SELECT * FROM reservation WHERE status = 'on'";
                $resnot = mysqli_query($conn, $sqlnot);
                $numnot = mysqli_num_rows($resnot);
                $sqlservnot = "SELECT * FROM service WHERE status = 'nouveau'";
                $resservnot = mysqli_query($conn, $sqlservnot);
                $numnot += $resservnot ? mysqli_num_rows($resservnot) : 0;
                $sql = "SELECT * FROM users WHERE id_users = '$id_p'";
                $res = mysqli_query($conn, $sql);
                $rows = mysqli_fetch_assoc($res);
            ?>
            <a href="notification.php?vue" class="icon-bell">
                <i class="fas fa-bell" id="<?php echo ($numnot > 0) ? 'noton' : 'notof'; ?>">
                    <?php if ($numnot > 0): ?>
                        <span class="num"><?php echo $numnot; ?></span>
                    <?php endif; ?>
                </i>
            </a>
            <img src="<?php echo '../images/pp_users/' . htmlspecialchars($rows['pp']); ?>" alt="Profil" class="pp">
        </div>
    </nav>
    <?php 
       $pageTitle = "Aide"; 
       include '../Asset/traitement/sidebar.php'; 
    ?>

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

    <div class="boxaide">
        <!-- Section : Introduction -->
        <div class="box">
            <h1 class="hea"><i class="fas fa-info-circle"></i> Introduction</h1>
            <p>Bienvenue dans l'application de gestion. Cette plateforme vous permet de gérer vos réservations, signalements, stocks, et tâches de maintenance de manière efficace.</p>
        </div>

        <!-- Section : Gestion des réservations -->
        <div class="box">
            <h1 class="hea"><i class="fas fa-calendar-alt"></i> Gestion des réservations</h1>
            <ol>
                <li>
                    <p><strong>Ajouter une réservation :</strong> Accédez à la page "Réservation", cliquez sur "Ajouter une réservation", remplissez le formulaire avec les informations nécessaires (nom, date, heure, salle, etc.), puis cliquez sur "Enregistrer".</p>
                    <i class="fas fa-plus-circle"></i>
                </li>
                <li>
                    <p><strong>Modifier une réservation :</strong> Cliquez sur l'icône "Modifier" à côté de la réservation, mettez à jour les informations, puis enregistrez les modifications.</p>
                    <i class="fas fa-edit"></i>
                </li>
                <li>
                    <p><strong>Supprimer une réservation :</strong> Cliquez sur l'icône "Supprimer" à côté de la réservation, puis confirmez la suppression.</p>
                    <i class="fas fa-trash"></i>
                </li>
            </ol>
        </div>

        <!-- Section : Gestion des signalements -->
        <div class="box">
            <h1 class="hea"><i class="fas fa-exclamation-circle"></i> Gestion des signalements</h1>
            <ol>
                <li>
                    <p><strong>Signaler un problème :</strong> Accédez à la page "Signalement", remplissez le formulaire avec les détails du problème rencontré, puis cliquez sur "Envoyer".</p>
                    <i class="fas fa-paper-plane"></i>
                </li>
            </ol>
        </div>


        <!-- Section : Maintenance -->
        <div class="box">
            <h1 class="hea"><i class="fas fa-tools"></i> Maintenance</h1>
            <ol>
                <li>
                    <p><strong>Ajouter une tâche :</strong> Accédez à la page "Maintenance", cliquez sur "Ajouter une tâche", remplissez les informations nécessaires, puis enregistrez.</p>
                    <i class="fas fa-plus-circle"></i>
                </li>
                <li>
                    <p><strong>Suivre les tâches :</strong> Consultez la liste des tâches en cours ou terminées pour suivre leur état.</p>
                    <i class="fas fa-tasks"></i>
                </li>
            </ol>
        </div>

        <!-- Section : Notifications -->
        <div class="box">
            <h1 class="hea"><i class="fas fa-bell"></i> Notifications</h1>
            <ol>
                <li>
                    <p><strong>Consulter les notifications :</strong> Cliquez sur l'icône de cloche dans la barre de navigation pour voir les notifications non lues.</p>
                    <i class="fas fa-eye"></i>
                </li>
                <li>
                    <p><strong>Marquer comme lues :</strong> Cliquez sur une notification pour la marquer comme lue.</p>
                    <i class="fas fa-check-circle"></i>
                </li>
            </ol>
        </div>

        <!-- Section : Navigation -->
        <div class="box">
            <h1 class="hea"><i class="fas fa-compass"></i> Navigation</h1>
            <p>Utilisez la barre de navigation pour accéder aux différentes sections de l'application. La barre de recherche vous permet de trouver rapidement des informations spécifiques.</p>
            <i class="fas fa-search"></i>
        </div>

        <!-- Section : Authentification -->
        <div class="box">
            <h1 class="hea"><i class="fas fa-user-lock"></i> Authentification</h1>
            <ol>
                <li>
                    <p><strong>Se connecter :</strong> Accédez à la page de connexion, entrez vos identifiants (nom d'utilisateur et mot de passe), puis cliquez sur "Se connecter".</p>
                    <i class="fas fa-sign-in-alt"></i>
                </li>
            </ol>
        </div>

        <!-- Section : Profil utilisateur -->
        <div class="box">
            <h1 class="hea"><i class="fas fa-user"></i> Profil utilisateur</h1>
            <ol>
                <li>
                    <p><strong>Consulter votre profil :</strong> Cliquez sur votre photo dans la barre de navigation pour accéder à votre profil.</p>
                    <i class="fas fa-user-circle"></i>
                </li>
                <li>
                    <p><strong>Modifier vos informations :</strong> Mettez à jour vos informations personnelles ou votre mot de passe, puis enregistrez les modifications.</p>
                    <i class="fas fa-edit"></i>
                </li>
            </ol>
        </div>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>
<?php } 
else{
    header("Location: login.php");
} ?>