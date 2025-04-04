<?php 
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
$id_p = $_SESSION['id_users'];

// Toggle availability logic
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $sqlCurrent = "SELECT disponibilite FROM salle WHERE id_salle = '$id'";
    $resCurrent = mysqli_query($conn, $sqlCurrent);
    if ($resCurrent && mysqli_num_rows($resCurrent) > 0) {
        $rowCurrent = mysqli_fetch_assoc($resCurrent);
        $current = $rowCurrent['disponibilite'];
        $newState = ($current === 'disponible') ? 'indisponible' : 'disponible';
        $sqlUpdate = "UPDATE salle SET disponibilite = '$newState' WHERE id_salle = '$id'";
        mysqli_query($conn, $sqlUpdate);
    }
    // Réponse instantanée si AJAX, sinon redirection classique
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'){
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'newState' => $newState]);
        exit;
    } else {
        header("Location: maintenance.php");
        exit;
    }
}

if (isset($_SESSION['id_users'])) { ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="../Outils Html/icomoon/style.css">
    <style>
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }
        
        .notification-icon {
            animation: bounce 1.5s infinite;
        }
        
        .form-container {
            display: none;
        }
        
        .form-container.active {
            display: block;
        }
        
        .modal {
            display: none;
        }
        
        .modal.active {
            display: flex;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans dark:bg-gray-900 dark:text-white">
    <?php 
       $pageTitle = "Maintenance"; 
       include '../Asset/traitement/sidebar.php'; 
    ?>
    
    <nav class="bg-blue-600 py-2 px-4 flex w-full right-0 absolute justify-between items-center text-white">
        <div class="flex items-center gap-3">
            <img src="../Images/IA.PNG" alt="Logo" class="h-10">
            <p class="text-lg font-semibold">Maintenance</p>
        </div>
        <div class="flex items-center gap-4">
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
            <a href="notification.php?vue" class="notification-icon relative text-white no-underline text-2xl inline-block cursor-pointer transition-transform duration-300 hover:scale-110">
                <span class="icon-bell"></span>
                <?php if ($numnot > 0) { ?>
                    <span class="absolute -top-1 -right-2 bg-red-600 text-white text-xs font-bold py-1 px-2 rounded-full shadow-md">
                        <?php echo $numnot; ?>
                    </span>
                <?php } ?>
            </a>
            <img src="<?php echo '../images/pp_users/' . @$rows['pp'] ?>" alt="User Profile" class="h-10 w-10 rounded-full">
        </div>
    </nav>

    <div class="ml-64 p-5 absolute top-16 w-4/5">
        <?php
        // Handle success and error messages
        if (isset($_SESSION['success_message'])) {
            echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4' role='alert'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4' role='alert'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
            unset($_SESSION['error_message']);
        }
        ?>
        <div class="text-center my-5">    
            <a href="#" id="addSalleBtn" class="inline-block px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold text-base transition-all duration-300 hover:scale-105">
                <i class="fas fa-plus"></i> Ajouter une salle
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 p-5">
        
            <?php 
            $sqlreq = "SELECT * FROM salle";
            $reqma = mysqli_query($conn, $sqlreq);
            while ($rowmai = mysqli_fetch_assoc($reqma)) {
                $id_sal = $rowmai['id_salle'];
                $sqlms = "SELECT * FROM ordinateurs WHERE id_salle = '$id_sal'";
                $reams = mysqli_query($conn, $sqlms);
                $num = mysqli_num_rows($reams);
                echo '
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 text-center shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg flex flex-col justify-between items-center space-y-4">
                        <div class="text-5xl text-blue-600 dark:text-blue-400">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <div class="text-center">
                            <p class="text-xl text-blue-600 dark:text-blue-400 my-1">' . htmlspecialchars($rowmai['nom_salle']) . '</p>
                            <p class="text-xl text-blue-600 dark:text-blue-400 my-1"><i class="fas fa-laptop"></i> ' . $num . ' Machines</p>
                            <a href="list_machine.php?id_salle=' . $rowmai['id_salle'] . '" class="inline-block mt-2 px-5 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-bold text-base transition-all duration-300 hover:scale-105"><i class="fas fa-eye"></i> Voir toutes les machines</a>
                        </div>
                        <div class="mt-2 flex flex-col gap-2">
                            <a href="maintenance.php?toggle=' . $id_sal . '" class="toggle-btn bg-yellow-600 hover:bg-yellow-700 text-white border-0 py-2 px-4 rounded inline-block text-base">
                                ' . (($rowmai['disponibilite'] == 'disponible') ? 'Marquer comme indisponible' : 'Rendre disponible') . '
                            </a>
                            <button class="delete-btn bg-red-600 hover:bg-red-700 text-white border-0 py-2 px-4 rounded flex items-center space-x-1 cursor-pointer text-base" data-id="' . $id_sal . '">
                                <i class="fas fa-trash"></i> <span class="ml-1">Supprimer</span>
                            </button>
                        </div>
                    </div>
                ';
            }
            ?>
        </div>


        <!-- Formulaire de création de salle -->
        <div class="form-container fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 p-5 rounded-xl shadow-lg z-50 w-96" id="formContainer">
            <form action="../Asset/traitement/maintenance.php" method="post" class="space-y-4">
                <h3 class="text-xl font-bold text-blue-600 dark:text-blue-400 mb-5">Créer une nouvelle salle</h3>
                
                <div>
                    <label for="nomsalle" class="block mb-1 font-bold text-gray-700 dark:text-gray-300">Nom de la salle :</label>
                    <input type="text" name="nomsalle" id="nomsalle" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded text-base focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label for="disponibilite" class="block mb-1 font-bold text-gray-700 dark:text-gray-300">Disponibilité :</label>
                    <select name="disponibilite" id="disponibilite" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded text-base focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="disponible">Disponible</option>
                        <option value="indisponible">Indisponible</option>
                    </select>
                </div>

                <div class="flex justify-between gap-3 mt-4">
                    <input type="submit" name="send" value="Créer" id="send" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded cursor-pointer font-bold text-base transition-colors">
                    <button type="button" id="closeFormBtn" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded cursor-pointer font-bold text-base transition-colors">Annuler</button>
                </div>
            </form>
        </div>

        <!-- Modal de confirmation de suppression -->
        <div class="modal fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 justify-center items-center z-50" id="deleteModal">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg text-center shadow-lg max-w-md mx-auto">
                <p class="text-xl mb-5 text-gray-800 dark:text-gray-200">Êtes-vous sûr de vouloir supprimer cette salle ?</p>
                <div class="flex justify-center gap-3">
                    <button id="confirmDelete" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded cursor-pointer text-base font-bold">Oui, supprimer</button>
                    <button id="cancelDelete" class="px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded cursor-pointer text-base font-bold">Annuler</button>
                </div>
            </div>
        </div>

        <script>
            // Dark mode detection
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            }
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
                if (event.matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            });

            const addSalleBtn = document.getElementById('addSalleBtn');
            const formContainer = document.getElementById('formContainer');
            const closeFormBtn = document.getElementById('closeFormBtn');

            addSalleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                formContainer.classList.add('active');
            });

            closeFormBtn.addEventListener('click', () => {
                formContainer.classList.remove('active');
            });

            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteModal = document.getElementById('deleteModal');
            const confirmDelete = document.getElementById('confirmDelete');
            const cancelDelete = document.getElementById('cancelDelete');
            let deleteId = null;

            // Ouvrir le modal de confirmation
            deleteButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    deleteId = button.getAttribute('data-id');
                    deleteModal.classList.add('active');
                });
            });

            // Confirmer la suppression
            confirmDelete.addEventListener('click', () => {
                if (deleteId) {
                    window.location.href = `../Asset/traitement/dropsalle.php?delete=${deleteId}`;
                }
            });

            // Annuler la suppression
            cancelDelete.addEventListener('click', () => {
                deleteModal.classList.remove('active');
                deleteId = null;
            });

            // Ajout de l'écouteur pour les boutons de basculement de disponibilité
            document.querySelectorAll('.toggle-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    fetch(btn.getAttribute('href'), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.status === 'success') {
                            btn.textContent = (data.newState === 'disponible') ? 'Marquer comme indisponible' : 'Rendre disponible';
                        }
                    })
                    .catch(error => console.error(error));
                });
            });
        </script>
    </div>
</body>

</html>
<?php } else {
    header("Location: login.php");
} ?>