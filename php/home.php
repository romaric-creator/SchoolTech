<?php
// Configuration de la base de données
$host = "localhost";
$user = "root";
$password = "";
$base = "my dashboard";

$conn = mysqli_connect($host, $user, $password);
$sqls = mysqli_select_db($conn, $base);

if (!$sqls) {
    header("Location: install.php#t1");
    exit();
}

include '../Asset/traitement/login.php';

// Vérification de la session utilisateur
// if (!isset($_SESSION['id_user'])) {
//     header("Location: login.php");
//     exit();
// }

$id_p = $_SESSION['id_users'];

// Traitement des messages de succès et d'erreur

include "../Asset/traitement/config.php";

// Récupération des notifications
$sqlnot = "SELECT * FROM reservation WHERE status = 'on'";
$resnot = mysqli_query($conn, $sqlnot);
$numnot = mysqli_num_rows($resnot);

$sqlservnot = "SELECT * FROM service WHERE status = 'nouveau'";
$resservnot = mysqli_query($conn, $sqlservnot);
$numnot += $resservnot ? mysqli_num_rows($resservnot) : 0;

// Récupération des informations de l'utilisateur
$sql = "SELECT * FROM users WHERE id_users = '$id_p'";
$res = mysqli_query($conn, $sql);
$rows = mysqli_fetch_assoc($res);

// Chemin de l'image de profil
$profile_image = !empty($rows['pp']) ? '../Images/pp_users/' . $rows['pp'] : '../Images/default_profile.png';

// Récupération des totaux pour les statistiques
$sql_users = "SELECT COUNT(*) AS total_users FROM users";
$res_users = mysqli_query($conn, $sql_users);
$data_users = mysqli_fetch_assoc($res_users);

$sql_reservations = "SELECT COUNT(*) AS total_reservations FROM reservation";
$res_reservations = mysqli_query($conn, $sql_reservations);
$data_reservations = mysqli_fetch_assoc($res_reservations);

$sql_salles = "SELECT COUNT(*) AS total_salles FROM salle";
$res_salles = mysqli_query($conn, $sql_salles);
$data_salles = mysqli_fetch_assoc($res_salles);

// Ajout des requêtes pour les salles disponibles et non disponibles
$sql_salles_available = "SELECT COUNT(*) AS available FROM salle WHERE disponibilite = 'disponible'";
$res_salles_available = mysqli_query($conn, $sql_salles_available);
$data_salles_available = mysqli_fetch_assoc($res_salles_available);

$sql_salles_unavailable = "SELECT COUNT(*) AS unavailable FROM salle WHERE disponibilite != 'disponible'";
$res_salles_unavailable = mysqli_query($conn, $sql_salles_unavailable);
$data_salles_unavailable = mysqli_fetch_assoc($res_salles_unavailable);

$sql_notif = "SELECT COUNT(*) AS total_notif FROM reservation WHERE status = 'on'";
$res_notif = mysqli_query($conn, $sql_notif);
$data_notif = mysqli_fetch_assoc($res_notif);

$sql_signalements = "SELECT COUNT(*) AS total_signalements FROM service";
$res_signalements = mysqli_query($conn, $sql_signalements);
$data_signalements = mysqli_fetch_assoc($res_signalements);

// Réservations de la semaine
$sql_week_reservations = "
    SELECT * 
    FROM reservation 
    WHERE YEARWEEK(date_res, 1) = YEARWEEK(CURDATE(), 1)
    ORDER BY date_res ASC, debh ASC";
$res_week_reservations = mysqli_query($conn, $sql_week_reservations);

// Récupérer les 5 dernières notifications
$sql_latest_notif = "SELECT * FROM reservation ORDER BY date_res DESC LIMIT 5";
$res_latest_notif = mysqli_query($conn, $sql_latest_notif);

// Récupérer les 5 derniers signalements
$sql_latest_signal = "SELECT * FROM service WHERE action = '' ORDER BY id_service  DESC LIMIT 5";
$res_latest_signal = mysqli_query($conn, $sql_latest_signal);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion des Ressources</title>
    <link rel="icon" href="../Images/IA.PNG">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script>
        // Fonction pour afficher les notifications toast

        // Toggle pour le menu mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('translate-x-0');
            sidebar.classList.toggle('-translate-x-full');
        }

        // Pour afficher/masquer les tooltips
        function toggleTooltip(id) {
            const tooltip = document.getElementById(id);
            tooltip.classList.toggle('hidden');
        }
    </script>
    <style>
        /* Styles personnalisés */
        .bg-primary {
            background-color: #007bff;
        }

        .text-primary {
            color: #007bff;
        }

        .border-primary {
            border-color: #007bff;
        }

        .hover\:bg-primary-dark:hover {
            background-color: #0056b3;
        }

        /* Animation pour le badge de notification */
        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-4px);
            }
        }

        .bounce {
            animation: bounce 1s infinite;
        }

        /* Transitions fluides */
        .transition-all {
            transition: all 0.3s ease;
        }

        /* Styliser la scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #007bff;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #0056b3;
        }

        /* Assurer que les tableaux sont responsive */
        .table-container {
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: #007bff #f1f1f1;
        }

        /* Style pour le tooltip personnalisé */
        .tooltip {
            position: relative;
        }

        .tooltip-content {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            white-space: nowrap;
            z-index: 10;
        }

        .tooltip-content::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: #333 transparent transparent transparent;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <!-- Sidebar -->
    <?php
    include '../Asset/traitement/sidebar.php'; ?>

    <!-- Navbar -->
    <div class="bg-primary shadow-md md:ml-64 transition-all">
        <div class="flex items-center justify-between p-4">
            <button class="text-white hover:text-gray-200 md:hidden focus:outline-none" onclick="toggleSidebar()">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <h1 class="text-white text-xl font-bold md:ml-0 ml-4">Dashboard</h1>

            <div class="flex items-center space-x-4">
                <a href="notification.php?vue" class="tooltip relative text-white hover:text-gray-200"
                    onmouseover="toggleTooltip('notif-tooltip')" onmouseout="toggleTooltip('notif-tooltip')">
                    <i class="fas fa-bell text-xl"></i>
                    <?php if ($numnot > 0) { ?>
                        <span
                            class="absolute -top-2 -right-2 px-2 py-0.5 bg-red-500 text-white rounded-full text-xs bounce">
                            <?php echo $numnot; ?>
                        </span>
                    <?php } ?>
                    <div id="notif-tooltip" class="tooltip-content hidden">
                        Notifications
                    </div>
                </a>

                <div class="relative tooltip" onclick="toggleUserMenu()" onmouseover="toggleTooltip('user-tooltip')"
                    onmouseout="toggleTooltip('user-tooltip')">
                    <img src="<?php echo $profile_image; ?>" alt="Profile"
                        class="h-10 w-10 rounded-full object-cover border-2 border-white cursor-pointer">
                    <div id="user-tooltip" class="tooltip-content hidden">
                        <?php echo htmlspecialchars($rows['nom'] . ' ' . $rows['prenom']); ?>
                    </div>

                    <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-50">
                        <div class="py-1">
                            <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Profil
                            </a>
                            <a href="settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i> Paramètres
                            </a>
                            <div class="border-t border-gray-200 my-1"></div>
                            <a href="../Asset/traitement/logout.php"
                                class="block px-4 py-2 text-sm text-red-500 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="md:ml-64 p-6 transition-all">
        <!-- Breadcrumbs -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="dashboard.php" class="text-gray-700 hover:text-primary">
                            <i class="fas fa-home mr-2"></i>Accueil
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                            <span class="text-gray-500">Dashboard</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Section Statistiques / Aperçu Global -->
        <div class="mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-pie text-primary mr-2"></i>Aperçu Global
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <!-- Carte Utilisateurs -->
                <div
                    class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Utilisateurs</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $data_users['total_users']; ?></p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-users text-primary text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="users.php" class="text-primary text-sm hover:underline">
                            Voir détails <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Carte Réservations -->
                <div
                    class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Réservations</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php echo $data_reservations['total_reservations']; ?></p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-calendar-check text-green-500 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="reservation.php" class="text-primary text-sm hover:underline">
                            Voir détails <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Carte Salles -->
                <div
                    class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Salles</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $data_salles['total_salles']; ?></p>
                            <p class="text-sm text-gray-500">Disponibles: <?php echo $data_salles_available['available']; ?></p>
                            <p class="text-sm text-gray-500">Non disponibles: <?php echo $data_salles_unavailable['unavailable']; ?></p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-door-open text-purple-500 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="salle.php" class="text-primary text-sm hover:underline">
                            Voir détails <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Carte Notifications -->
                <div
                    class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Notifications</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $data_notif['total_notif']; ?></p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-bell text-yellow-500 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="notification.php" class="text-primary text-sm hover:underline">
                            Voir détails <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Carte Signalements -->
                <div
                    class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Signalements</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php echo $data_signalements['total_signalements']; ?></p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="service.php" class="text-primary text-sm hover:underline">
                            Voir détails <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Réservations de la semaine -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-calendar-week text-primary mr-2"></i>Réservations de la semaine
                </h2>
                <a href="reservation.php" class="text-primary hover:underline text-sm font-medium">
                    Voir toutes les réservations <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="table-container">
                    <?php if (mysqli_num_rows($res_week_reservations) > 0): ?>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-user mr-1"></i> Nom
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-door-open mr-1"></i> Salle
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-calendar-day mr-1"></i> Date
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-clock mr-1"></i> Début
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-clock mr-1"></i> Fin
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-info-circle mr-1"></i> Statut
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php while ($row = mysqli_fetch_assoc($res_week_reservations)): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($row['nom_us']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                <?php echo htmlspecialchars($row['nom_salle']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                <?php echo htmlspecialchars($row['date_res']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                <?php echo htmlspecialchars($row['debh']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                <?php echo htmlspecialchars($row['debf']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($row['status'] == 'on'): ?>
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    En attente
                                                </span>
                                            <?php elseif ($row['status'] == 'valider'): ?>
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Validée
                                                </span>
                                            <?php else: ?>
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    <?php echo htmlspecialchars($row['status']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="p-6 text-center">
                            <i class="fas fa-calendar-times text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-500">Aucune réservation n'est prévue pour cette semaine.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Section Notifications & Signalements -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Notifications Récentes -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-bell text-primary mr-2"></i>Notifications Récentes
                    </h2>
                    <a href="notification.php" class="text-primary hover:underline text-sm font-medium">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if (mysqli_num_rows($res_latest_notif) > 0): ?>
                        <ul class="divide-y divide-gray-200">
                            <?php while ($notif = mysqli_fetch_assoc($res_latest_notif)): ?>
                                <li class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 mt-1">
                                            <i class="fas fa-calendar-check text-primary"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($notif['nom_us']); ?>
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Salle: <?php echo htmlspecialchars($notif['nom_salle']); ?>
                                                le <?php echo htmlspecialchars($notif['date_res']); ?>
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                En attente
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <div class="p-6 text-center">
                            <i class="fas fa-check-circle text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-500">Aucune notification récente.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Signalements Récents -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-exclamation-triangle text-primary mr-2"></i>Signalements Récents
                    </h2>
                    <a href="service.php" class="text-primary hover:underline text-sm font-medium">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if (mysqli_num_rows($res_latest_signal) > 0): ?>
                        <ul class="divide-y divide-gray-200">
                            <?php while ($signal = mysqli_fetch_assoc($res_latest_signal)): ?>
                                <li class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 mt-1">
                                            <i class="fas fa-exclamation-circle text-red-500"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">
                                                ID: <?php echo htmlspecialchars($signal['id_service']); ?>
                                            </p>
                                            <p class="text-sm text-gray-500 truncate">
                                                <?php
                                                echo isset($signal['contenu'])
                                                    ? htmlspecialchars($signal['contenu'])
                                                    : "Signalement sans description";
                                                ?>
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <?php if (isset($signal['status']) && $signal['status'] == 'nouveau'): ?>
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Nouveau
                                                </span>
                                            <?php elseif (isset($signal['status']) && $signal['status'] == 'en_cours'): ?>
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    En cours
                                                </span>
                                            <?php elseif (isset($signal['status']) && $signal['status'] == 'resolu'): ?>
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Résolu
                                                </span>
                                            <?php else: ?>
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    <?php echo isset($signal['status']) ? htmlspecialchars($signal['status']) : "Inconnu"; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <div class="p-6 text-center">
                            <i class="fas fa-check-circle text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-500">Aucun signalement récent.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Section Actions Rapides -->
        <div class="mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-bolt text-primary mr-2"></i>Actions Rapides
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <a href="reservation.php?action=new"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center mb-3">
                        <i class="fas fa-calendar-plus text-white text-xl"></i>
                    </div>
                    <h3 class="text-gray-800 font-medium">Nouvelle Réservation</h3>
                    <p class="text-gray-500 text-sm mt-1">Réserver une salle</p>
                </a>

                <a href="service.php?action=new"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center mb-3">
                        <i class="fas fa-exclamation-circle text-white text-xl"></i>
                    </div>
                    <h3 class="text-gray-800 font-medium">Nouveau Signalement</h3>
                    <p class="text-gray-500 text-sm mt-1">Signaler un problème</p>
                </a>

                <a href="notification.php"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 rounded-full bg-yellow-500 flex items-center justify-center mb-3">
                        <i class="fas fa-bell text-white text-xl"></i>
                    </div>
                    <h3 class="text-gray-800 font-medium">Gérer Notifications</h3>
                    <p class="text-gray-500 text-sm mt-1">Voir toutes les notifications</p>
                </a>

                <a href="profile.php"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-all transform hover:-translate-y-1 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 rounded-full bg-purple-500 flex items-center justify-center mb-3">
                        <i class="fas fa-user-cog text-white text-xl"></i>
                    </div>
                    <h3 class="text-gray-800 font-medium">Mon Profil</h3>
                    <p class="text-gray-500 text-sm mt-1">Modifier votre profil</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-primary text-white py-3 text-center text-sm md:ml-64 transition-all">
        &copy; <?php echo date('Y'); ?> SchoolITech - Tous droits réservés
    </div>

    <script>
        // Toggle user menu
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        }

        // Close user menu when clicking outside
        document.addEventListener('click', function (event) {
            const userMenu = document.getElementById('userMenu');
            const userImage = document.querySelector('.relative.tooltip img');

            if (!userMenu.contains(event.target) && event.target !== userImage) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
</body>

</html>