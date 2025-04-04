<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $base = "my dashboard";

    $conn = mysqli_connect($host, $user, $password);
    $sqls = mysqli_select_db($conn, $base);
    if (!$sqls) {
        header("Location: install.php#t1");
    }
    include '../Asset/traitement/login.php';
    $id_p = $_SESSION['id_users'];
    if (isset($_SESSION['id_users'])) { ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Réservation</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
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
                
                .modal {
                    display: none;
                }
                
                .modal.active {
                    display: flex;
                }
                
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: scale(0.9);
                    }
                    to {
                        opacity: 1;
                        transform: scale(1);
                    }
                }
                
                .modal-animation {
                    animation: fadeIn 0.3s ease-in-out;
                }
            </style>
        </head>

        <body class="bg-gray-100 font-sans dark:bg-gray-900 dark:text-white">
            <?php 
            $pageTitle = "Réservation"; 
            include '../Asset/traitement/sidebar.php'; 
            ?>
            
            <!-- Main Content -->
            <div class="ml-64 p-0">
                <nav class="bg-blue-600 text-white py-2 px-4 flex justify-between items-center">
                    <!-- Left Section -->
                    <div class="flex items-center gap-5">
                        <h1 class="text-xl font-bold">Réservation</h1>
                    </div>

                    <!-- Center Section -->
                    <div class="flex items-center gap-2">
                        <form id="searchForm" onsubmit="return false;" class="flex items-center">
                            <input type="search" name="search" id="searchInput" class="px-3 py-2 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Rechercher nom ou salle..." onkeyup="searchReservations()">
                            <label for="search" class="ml-2 cursor-pointer text-white text-xl">
                                <i class="fas fa-search"></i>
                            </label>
                        </form>
                    </div>

                    <!-- Right Section -->
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
                            <i class="fas fa-bell" id="<?php echo ($numnot > 0) ? 'noton' : 'notof'; ?>"></i>
                            <?php if ($numnot > 0) { ?>
                                <span class="absolute -top-1 -right-2 bg-red-600 text-white text-xs font-bold py-1 px-2 rounded-full shadow-md">
                                    <?php echo $numnot; ?>
                                </span>
                            <?php } ?>
                        </a>
                        <a href="users.php?id_us=<?php echo $id_p ?>" class="ml-2">
                            <img src="<?php echo '../images/pp_users/' . @$rows['pp'] ?>" alt="User Profile" class="w-10 h-10 rounded-full">
                        </a>
                    </div>
                </nav>

                <?php
                // Handle success and error messages
                if (isset($_SESSION['success_message'])) {
                    echo "<div class='mx-5 mt-4 px-4 py-3 rounded relative bg-green-100 border border-green-400 text-green-700' role='alert'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
                    unset($_SESSION['success_message']);
                }

                if (isset($_SESSION['error_message'])) {
                    echo "<div class='mx-5 mt-4 px-4 py-3 rounded relative bg-red-100 border border-red-400 text-red-700' role='alert'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
                    unset($_SESSION['error_message']);
                }
                ?>

                <div class="m-5 bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <!-- Bouton pour ajouter une nouvelle réservation -->
                    <div class="mb-4">
                        <a href="#reservationModal" id="openModal" class="inline-block px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold text-base transition-all duration-300 hover:scale-105">
                            <i class="fas fa-plus mr-2"></i> Ajouter une réservation
                        </a>
                    </div>

                    <!-- Table container -->
                    <div class="overflow-x-auto">
                        <table class="w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-blue-600 text-white">
                                    <th class="py-3 px-4 text-left">N°</th>
                                    <th class="py-3 px-4 text-left">Nom</th>
                                    <th class="py-3 px-4 text-left">Téléphone</th>
                                    <th class="py-3 px-4 text-left">Date</th>
                                    <th class="py-3 px-4 text-left">Salle</th>
                                    <th class="py-3 px-4 text-left">Début</th>
                                    <th class="py-3 px-4 text-left">Fin</th>
                                    <th class="py-3 px-4 text-left">Modifier</th>
                                    <th class="py-3 px-4 text-left">Supprimer</th>
                                </tr>
                            </thead>
                            <tbody id="reservationTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <?php
                                $sql = "SELECT * FROM reservation ORDER BY id_reservation DESC";
                                $reqr = mysqli_query($conn, $sql);
                                $num = mysqli_num_rows($reqr);
                                $i = 0;
                                if ($num == 0) {
                                    echo '<tr><td colspan="9" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">Aucun enregistrement.</td></tr>';
                                } else {
                                    while ($row = mysqli_fetch_assoc($reqr)) {
                                        $i++;
                                        echo '
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <td class="py-3 px-4">' . $i . '</td>
                                            <td class="py-3 px-4">' . $row['nom_us'] . '</td>
                                            <td class="py-3 px-4">' . $row['tel'] . '</td>
                                            <td class="py-3 px-4">' . $row['date_res'] . '</td>
                                            <td class="py-3 px-4">' . $row['nom_salle'] . '</td>
                                            <td class="py-3 px-4">' . $row['debh'] . '</td>
                                            <td class="py-3 px-4">' . $row['debf'] . '</td>
                                            <td class="py-3 px-4">
                                                <a href="#reservationModal" onclick="openEditModal(' . htmlspecialchars(json_encode($row)) . ')" class="inline-block px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm transition-colors">
                                                    <i class="fas fa-edit mr-1"></i> Modifier
                                                </a>
                                            </td>
                                            <td class="py-3 px-4">
                                                <a href="#deleteModal" onclick="openDeleteModal(' . $row['id_reservation'] . ')" class="inline-block px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm transition-colors">
                                                    <i class="fas fa-trash mr-1"></i> Supprimer
                                                </a>
                                            </td>
                                        </tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal pour ajouter ou modifier une réservation -->
                <div id="reservationModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                    <div class="modal-animation relative bg-white dark:bg-gray-800 w-full max-w-md mx-auto rounded-xl shadow-lg p-6">
                        <h2 id="modalTitle" class="text-xl font-bold text-blue-600 dark:text-blue-400 mb-5 text-center">Ajouter une réservation</h2>
                        <span class="close absolute top-2 right-3 text-2xl font-bold text-gray-500 hover:text-red-500 cursor-pointer" id="closeModal">&times;</span>
                        
                        <form action="../Asset/traitement/reservation.php" method="POST" id="reservationForm" class="space-y-4">
                            <input type="hidden" name="id_reservation" id="id_reservation">
                            
                            <div>
                                <label for="nom" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Nom :</label>
                                <input type="text" id="nom" name="nom" placeholder="Entrez le nom" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label for="numero" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Numéro de téléphone :</label>
                                <input type="text" id="numero" name="numero" placeholder="Entrez le numéro" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label for="date" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Date :</label>
                                <input type="date" id="date" name="date" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label for="heuredeb" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Heure de début :</label>
                                <input type="time" id="heuredeb" name="heuredeb" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label for="heurefin" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Heure de fin :</label>
                                <input type="time" id="heurefin" name="heurefin" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label for="salle" class="block font-semibold mb-1 text-gray-700 dark:text-gray-300">Salle :</label>
                                <select id="salle" name="salle" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Sélectionnez une salle</option>
                                    <?php
                                    $sql_salles = "SELECT * FROM salle WHERE disponibilite = 'disponible'";
                                    $result_salles = mysqli_query($conn, $sql_salles);
                                    while ($row_salle = mysqli_fetch_assoc($result_salles)) {
                                        echo '<option value="' . $row_salle['nom_salle'] . '">' . $row_salle['nom_salle'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <button type="submit" name="send" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded transition-colors">
                                Enregistrer
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Modal pour confirmer la suppression -->
                <div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
                    <div class="modal-animation relative bg-white dark:bg-gray-800 w-full max-w-md mx-auto rounded-xl shadow-lg p-6 text-center">
                        <h2 class="text-xl font-bold text-red-600 mb-4">Confirmer la suppression</h2>
                        <span class="close absolute top-2 right-3 text-2xl font-bold text-gray-500 hover:text-red-500 cursor-pointer" id="cancelDelete">&times;</span>
                        
                        <p class="text-gray-700 dark:text-gray-300 mb-6">Êtes-vous sûr de vouloir supprimer cette réservation ? Cette action est irréversible.</p>
                        
                        <form action="../Asset/traitement/reservation.php" method="POST">
                            <input type="hidden" id="delete_id_reservation" name="id_reservation">
                            <div class="flex justify-center gap-3">
                                <button type="button" id="cancelDeleteBtn" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded font-semibold transition-colors">
                                    Annuler
                                </button>
                                <button type="submit" name="delete" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded font-semibold transition-colors">
                                    Supprimer
                                </button>
                            </div>
                        </form>
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
            
                // Ouvrir le modal pour ajouter une réservation
                document.getElementById("openModal").addEventListener("click", function (e) {
                    e.preventDefault();
                    document.getElementById("reservationModal").classList.add("active");
                    document.getElementById("modalTitle").innerText = "Ajouter une réservation";
                    document.getElementById("reservationForm").reset();
                    document.getElementById("id_reservation").value = ""; // Réinitialiser l'ID pour un ajout
                });

                // Fermer le modal de création ou de modification
                document.getElementById("closeModal").addEventListener("click", function () {
                    document.getElementById("reservationModal").classList.remove("active");
                });

                // Fermer le modal si l'utilisateur clique en dehors du contenu
                window.addEventListener("click", function (event) {
                    const modal = document.getElementById("reservationModal");
                    if (event.target === modal) {
                        modal.classList.remove("active");
                    }
                });

                // Ouvrir le modal pour modifier une réservation
                function openEditModal(data) {
                    document.getElementById("reservationModal").classList.add("active");
                    document.getElementById("modalTitle").innerText = "Modifier une réservation";
                    document.getElementById("id_reservation").value = data.id_reservation;
                    document.getElementById("nom").value = data.nom_us;
                    document.getElementById("numero").value = data.tel;
                    document.getElementById("date").value = data.date_res;
                    document.getElementById("heuredeb").value = data.debh;
                    document.getElementById("heurefin").value = data.debf;
                    document.getElementById("salle").value = data.nom_salle;
                }

                // Ouvrir le modal pour confirmer la suppression
                function openDeleteModal(id) {
                    document.getElementById("deleteModal").classList.add("active");
                    document.getElementById("delete_id_reservation").value = id;
                }

                // Fermer le modal de suppression
                document.getElementById("cancelDelete").addEventListener("click", function () {
                    document.getElementById("deleteModal").classList.remove("active");
                });
                
                document.getElementById("cancelDeleteBtn").addEventListener("click", function () {
                    document.getElementById("deleteModal").classList.remove("active");
                });

                // Rechercher des réservations
                function searchReservations() {
                    const query = document.getElementById("searchInput").value;

                    // Effectuer une requête AJAX
                    const xhr = new XMLHttpRequest();
                    xhr.open("GET", `../Asset/traitement/search_reservation.php?query=${encodeURIComponent(query)}`, true);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Mettre à jour le tableau des réservations avec les résultats
                            document.getElementById("reservationTableBody").innerHTML = xhr.responseText;
                        }
                    };
                    xhr.send();
                }
            </script>
        </body>

        </html>
    <?php } else {
        header("Location: login.php");
    } ?>