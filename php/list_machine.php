<?php
$host = "localhost";
$user = "root";
$password = "";
$base = "my dashboard";

$conn = mysqli_connect($host, $user, $password);
$sqls = mysqli_select_db($conn, $base);
if ($sqls) {
} else {
    header("Location: install.php#t1");
}
include '../Asset/traitement/login.php';

if (isset($_SESSION['id_users'])) { ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- fontaweson -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
        <title>Liste des machines</title>
        <link rel="stylesheet" href="../Outils Html/icomoon/style.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f6f9;
                color: #333;
            }

            .h {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff;
            }

            .h .go {
                display: flex;
                align-items: center;
                text-decoration: none;
                color: #fff;
            }

            .h .go span {
                font-size: 1.5rem;
                margin-right: 10px;
            }

            .h .text {
                font-size: 1.5rem;
                font-weight: bold;
            }

            .list_mach {
                display: flex;
                flex-direction: column;
                gap: 20px;
                padding: 20px 0px 0px 250px;
            }

            .row {
                display: grid;
                grid-template-columns: repeat(4, 1fr); /* 4 colonnes */
                gap: 20px;
                margin-bottom: 20px;
            }

            .row-title {
                grid-column: span 4; /* Le titre occupe toute la largeur de la rangée */
                font-size: 1.2rem;
                font-weight: bold;
                color: #007bff;
                margin-bottom: 10px;
                text-align: left;
            }

            .mach {
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 20px;
                text-align: center;
                transition: transform 0.3s, box-shadow 0.3s;
            }

            .mach:hover {
                transform: translateY(-5px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            }

            .mach img {
                max-width: 100px;
                margin-bottom: 15px;
            }

            .mach .machine-id {
                font-size: 1rem;
                font-weight: bold;
                color: #007bff;
                margin-bottom: 10px;
            }

            .mach table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            .mach table td {
                padding: 5px;
                font-size: 0.9rem;
                color: #555;
            }

            .mach table td:first-child {
                font-weight: bold;
                color: #333;
                display: flex;
                align-items: center;
                gap: 5px; /* Espacement entre l'icône et le texte */
            }

            .mach table td:first-child span {
                font-size: 1.2rem;
                color: #007bff; /* Couleur des icônes */
            }

            .actions {
                margin-top: 10px;
            }

            .actions .btn {
                display: inline-block;
                padding: 5px 10px;
                border-radius: 5px;
                text-decoration: none;
                color: #fff;
                font-size: 0.9rem;
            }

            .actions .btn-edit {
                background-color: #007bff;
            }

            .actions .btn-edit:hover {
                background-color: #0056b3;
            }

            /* Style pour le bouton Supprimer */
            .actions .btn-delete {
                background-color: #dc3545; /* Rouge */
                color: #fff;
                border: none;
                padding: 8px 12px;
                border-radius: 5px;
                font-size: 0.9rem;
                cursor: pointer;
                transition: background-color 0.3s, transform 0.2s;
            }

            .actions .btn-delete:hover {
                background-color: #b02a37; /* Rouge plus foncé */
                transform: scale(1.05); /* Légère mise en avant */
            }

            .boop {
                text-align: center;
                margin: 20px 0;
            }

            .boop .right {
                display: inline-block;
                padding: 10px 20px;
                background: #28a745;
                color: #fff;
                text-decoration: none;
                border-radius: 8px;
                font-size: 1rem;
                font-weight: bold;
                transition: background 0.3s, transform 0.3s;
            }

            .boop .right:hover {
                background: #218838;
                transform: scale(1.05);
            }

            /* Modal */
            .formres {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: #fff;
                padding: 20px;
                border-radius: 12px;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                z-index: 1000;
                width: 500px;
                max-height: 80%;
                overflow-y: auto;
                animation: fadeIn 0.3s ease-in-out;
            }

            /* Animation d'apparition */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translate(-50%, -60%);
                }
                to {
                    opacity: 1;
                    transform: translate(-50%, -50%);
                }
            }

            .formres.active {
                display: block;
            }

            /* En-tête du modal */
            .formres .hed {
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: #007bff;
                color: #fff;
                padding: 10px 20px;
                border-radius: 12px 12px 0 0;
            }

            .formres .hed p {
                font-size: 1.5rem;
                font-weight: bold;
            }

            .formres .hed .close-btn {
                background: none;
                border: none;
                font-size: 1.5rem;
                color: #fff;
                cursor: pointer;
            }

            /* Corps du formulaire */
            .form-body {
                display: flex;
                flex-direction: column;
                gap: 15px;
                padding: 20px 0;
            }

            /* Champs du formulaire */
            .form-group {
                display: flex;
                flex-direction: column;
            }

            .form-group label {
                font-size: 1rem;
                font-weight: bold;
                color: #333;
                margin-bottom: 5px;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .form-group input {
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 1rem;
                width: 100%;
                transition: border-color 0.3s, box-shadow 0.3s;
            }

            .form-group input:focus {
                border-color: #007bff;
                outline: none;
                box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            }

            /* Boutons */
            .bot {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 20px;
            }

            .bot button,
            .bot input[type="submit"] {
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                font-size: 1rem;
                cursor: pointer;
                transition: background 0.3s, transform 0.3s;
            }

            .bot .anl {
                background: #dc3545;
                color: #fff;
                text-decoration: none;
            }

            .bot .anl:hover {
                background: #b02a37;
                transform: scale(1.05);
            }

            .bot input[type="submit"] {
                background: #007bff;
                color: #fff;
            }

            .bot input[type="submit"]:hover {
                background: #0056b3;
                transform: scale(1.05);
            }

            /* Sidebar */
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 17%;
                height: 100%;
                background-color: #007bff;
                color: #fff;
                display: flex;
                flex-direction: column;
                padding: 20px 0;
                z-index: 1000;
            }

            .sidebar-header {
                text-align: center;
                margin-bottom: 20px;
            }

            .sidebar-header h3 {
                font-size: 1.5rem;
                font-weight: bold;
                color: #fff;
            }

            .sidebar-menu {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .sidebar-menu li {
                margin: 15px 0;
            }

            .sidebar-menu li a {
                text-decoration: none;
                color: #fff;
                font-size: 1rem;
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 20px;
                border-radius: 5px;
                transition: background 0.3s, transform 0.3s;
            }

            .sidebar-menu li a:hover {
                background-color: #0056b3;
                transform: translateX(5px);
            }

            .sidebar-menu li a span {
                font-size: 1.2rem;
            }

            /* Main content adjustment */
            .main-content {
                margin-left: 26px;
            }
        </style>
    </head>

    <body  class="bg-gray-100 font-sans">
    <?php
    $title="Liste des machines";
    include '../Asset/traitement/sidebar.php';
    ?>
        <div class="main-content">
            <div class="h" >
                <div></div>
                <div class="text">Machines</div>
            </div>
            
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
            <div class="list_mach">
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
            <div class="boop">
                <a href="#addsalle" class="right" id="openModal"><span class="icon-plus"></span> Ajouter une machine</a>
            </div>
                <?php
                if (isset($_GET['id_salle'])) {
                    $id_sl = $_GET['id_salle'];
                }
                // Afficher les machines par rangée
                $sqlmach = "SELECT * FROM ordinateurs WHERE id_salle = '$id_sl' ORDER BY ranger, id_ordinateur";
                $reqmach = mysqli_query($conn, $sqlmach);

                $currentRow = 0; // Rangée actuelle
                while ($rowmach = mysqli_fetch_assoc($reqmach)) {
                    if ($currentRow != $rowmach['ranger']) {
                        // Fermer la rangée précédente
                        if ($currentRow != 0) {
                            echo '</div>';
                        }

                        // Ouvrir une nouvelle rangée
                        $currentRow = $rowmach['ranger'];
                        echo '<div class="row">';
                        echo '<p class="row-title">Rangée ' . $currentRow . '</p>';
                    }

                    // Afficher une machine
                    echo '
                    <div class="mach">
                        <img src="../Images/logo Pc.png" alt="Machine">
                        <table>
                            <tr>
                                <td><span class="icon-laptop"></span> Nom :</td>
                                <td>' . htmlspecialchars($rowmach['nom_ordi']) . '</td>
                            </tr>
                            <tr>
                                <td><span class="icon-windows"></span> SE :</td>
                                <td>' . htmlspecialchars($rowmach['Systeme_E']) . '</td>
                            </tr>
                            <tr>
                                <td><span class="icon-memory"></span> RAM :</td>
                                <td>' . htmlspecialchars($rowmach['ram']) . ' Go</td>
                            </tr>
                            <tr>
                                <td><span class="icon-database"></span> Disque dur :</td>
                                <td>' . htmlspecialchars($rowmach['Disque']) . ' Go</td>
                            </tr>
                            <tr>
                                <td><span class="icon-cpu"></span> Processeur :</td>
                                <td>' . htmlspecialchars($rowmach['proces']) . '</td>
                            </tr>
                        </table>
                        <div class="actions">
                            <a href="edit_machine.php?id=' . $rowmach['id_ordinateur'] . '&idsalle=' . $rowmach['id_salle'] . '" class="btn btn-edit">Modifier</a>
                            <button type="button" class="btn btn-delete" onclick="openDeleteModal(' . $rowmach['id_ordinateur'] . ',' . $rowmach['id_salle'] . ')">Supprimer</button>
                        </div>
                    </div>';
                }

                // Fermer la dernière rangée
                if ($currentRow != 0) {
                    echo '</div>';
                }
                ?>
            </div>

            <div class="formres" id="modalForm">
                <form action="../Asset/traitement/computer.php" method="post">
                    <div class="hed">
                        <p>Ajouter une machine</p>
                        <button type="button" class="close-btn" id="closeModal"><span class="icon-close"></span></button>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label for="nom"><span class="icon-laptop"></span> Nom de la machine</label>
                            <input type="text" name="nom" id="nom" placeholder="Ex : PC Bureau" required>
                        </div>
                        <div class="form-group">
                            <label for="se"><span class="icon-windows"></span> Système d'exploitation</label>
                            <input type="text" name="se" id="se" placeholder="Ex : Windows 10" required>
                        </div>
                        <div class="form-group">
                            <label for="dd"><span class="icon-database"></span> Disque dur (en Go)</label>
                            <input type="number" name="dd" id="dd" placeholder="Ex : 500" required>
                        </div>
                        <div class="form-group">
                            <label for="ra"><span class="icon-memory"></span> RAM (en Go)</label>
                            <input type="number" name="ra" id="ra" placeholder="Ex : 8" required>
                        </div>
                        <div class="form-group">
                            <label for="pr"><span class="icon-cpu"></span> Processeur</label>
                            <input type="text" name="pr" id="pr" placeholder="Ex : Intel i5" required>
                        </div>
                        <div class="form-group">
                            <label for="date"><span class="icon-calendar"></span> Date de maintenance</label>
                            <input type="date" name="date" id="date" required>
                        </div>
                        <div class="form-group">
                            <label for="ranger"><span class="icon-grid"></span> Rangée</label>
                            <select name="ranger" id="ranger" required>
                                <?php
                                // Générer dynamiquement les options des rangées
                                for ($i = 1; $i <= 4; $i++) {
                                    echo '<option value="' . $i . '">Rangée ' . $i . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $_GET['id_salle'] ?>">
                    <div class="bot">
                        <button type="button" class="anl" id="closeModalBottom">Annuler</button>
                        <input type="submit" value="Ajouter" name="send">
                    </div>
                </form>
            </div>
            <!-- Modal de confirmation pour la suppression -->
            <div id="deleteModal" class="formres">
                <div class="hed">
                    <p>Confirmer la suppression</p>
                    <button type="button" class="close-btn" id="closeDeleteModal"><span class="icon-close"></span></button>
                </div>
                <div class="form-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette machine ?</p>
                    <form id="deleteForm" method="post" action="delete_machine.php">
                        <input type="hidden" name="id" id="deleteMachineId">
                        <input type="hidden" name="idsalle" id="deleteMachineIdsalle">
                        <div class="bot">
                            <button type="button" class="anl" id="cancelDelete">Annuler</button>
                            <input type="submit" name="idsu" value="Supprimer" class="btn btn-delete">
                        </div>
                    </form>
                </div>
            </div>
            <script>
                const openModal = document.getElementById('openModal');
                const closeModal = document.getElementById('closeModal');
                const closeModalBottom = document.getElementById('closeModalBottom');
                const modalForm = document.getElementById('modalForm');

                // Ouvrir le modal
                openModal.addEventListener('click', (e) => {
                    e.preventDefault();
                    modalForm.classList.add('active');
                });

                // Fermer le modal (bouton en haut)
                closeModal.addEventListener('click', (e) => {
                    e.preventDefault();
                    modalForm.classList.remove('active');
                });

                // Fermer le modal (bouton en bas)
                closeModalBottom.addEventListener('click', (e) => {
                    e.preventDefault();
                    modalForm.classList.remove('active');
                });

                // Fermer le modal en cliquant en dehors (optionnel)
                window.addEventListener('click', (e) => {
                    if (e.target === modalForm) {
                        modalForm.classList.remove('active');
                    }
                });

                const deleteModal = document.getElementById('deleteModal');
                const closeDeleteModal = document.getElementById('closeDeleteModal');
                const cancelDelete = document.getElementById('cancelDelete');
                const deleteForm = document.getElementById('deleteForm');
                const deleteMachineId = document.getElementById('deleteMachineId');
                const deleteMachineIdsalle = document.getElementById('deleteMachineIdsalle');

                // Ouvrir le modal de suppression
                function openDeleteModal(machineId,idsalle) {
                    deleteMachineId.value = machineId; 
                    deleteMachineIdsalle.value = idsalle; 
                    deleteModal.classList.add('active');
                }

                // Fermer le modal de suppression
                closeDeleteModal.addEventListener('click', () => {
                    deleteModal.classList.remove('active');
                });

                cancelDelete.addEventListener('click', () => {
                    deleteModal.classList.remove('active');
                });

                // Fermer le modal en cliquant en dehors (optionnel)
                window.addEventListener('click', (e) => {
                    if (e.target === deleteModal) {
                        deleteModal.classList.remove('active');
                    }
                });
            </script>
        </div>
    </body>

    </html>
<?php } else {
    header("Location: login.php");
} ?>