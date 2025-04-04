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
$sql = "SELECT * FROM users WHERE id_users = '$id_p'";
$res = mysqli_query($conn, $sql);
$rows = mysqli_fetch_assoc($res);

if (isset($_SESSION['id_users'])) { 
    // Check if the user is an admin
    $isAdmin = $rows['status'] === 'admin'; 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
a{
    text-decoration: none;
}
        .header {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .header .logo img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
        }
    </style>
</head>

<body  class="bg-gray-100 font-sans">
        <!-- Sidebar -->
        <?php 
       $pageTitle = "Profil"; 
       include '../Asset/traitement/sidebar.php'; 
    ?>
    <!-- Header -->
    <div class="header bg-primary shadow-md md:ml-64 transition-all">
        <div></div>
        <div class="logo">
            <img src="<?php echo '../images/pp_users/' . htmlspecialchars($rows['pp']); ?>" alt="Profil">
        </div>
    </div>



    <!-- Main Content -->
    <div class="main-content">
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
        <h1>Bienvenue, <?php echo htmlspecialchars($rows['nom']); ?> !</h1>
        <p>Utilisez le menu pour gérer vos paramètres.</p>

        <!-- Cartes d'informations -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Profil</h5>
                        <p class="card-text">Modifiez vos informations personnelles.</p>
                        <a href="#" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-user-edit"></i> Modifier
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Mot de passe</h5>
                        <p class="card-text">Changez votre mot de passe pour plus de sécurité.</p>
                        <a href="#" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-key"></i> Modifier
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Photo de profil</h5>
                        <p class="card-text">Mettez à jour votre photo de profil.</p>
                        <a href="#" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#changePhotoModal">
                            <i class="fas fa-camera"></i> Modifier
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($isAdmin) { // Only show admin-specific sections to admins ?>
        <!-- Button to trigger Add User Modal -->
        <div class="mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-user-plus"></i> Ajouter un utilisateur
            </button>
        </div>

        <!-- Tableau des utilisateurs -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5>Liste des utilisateurs</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_users = "SELECT * FROM users";
                        $res_users = mysqli_query($conn, $sql_users);
                        $count = 1;
                        while ($user = mysqli_fetch_assoc($res_users)) {
                            echo "<tr>
                                    <td>{$count}</td>
                                    <td>" . htmlspecialchars($user['nom']) . "</td>
                                    <td>" . htmlspecialchars($user['email']) . "</td>
                                    <td>" . htmlspecialchars($user['status']) . "</td>
                                    <td>
                                        <a href='#' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#editProfileModal'><i class='fas fa-edit'></i></a>
                                        <a href='#' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a>
                                    </td>
                                  </tr>";
                            $count++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Modals -->
    <!-- Modal pour modifier le profil -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Modifier le profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../Asset/traitement/upusers.php" method="post">
                    <div class="modal-body">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" id="nom" class="form-control" value="<?php echo htmlspecialchars($rows['nom']); ?>">

                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($rows['email']); ?>">
                        <input type="hidden" name="hid" value="<?php echo htmlspecialchars($id_p); ?>">
                        <label for="numero">Numéro</label>
                        <input type="text" name="numero" id="numero" class="form-control" value="<?php echo htmlspecialchars($rows['numero']); ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <input type="submit" class="btn btn-primary" value="Modifier" name="send">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal pour changer le mot de passe -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Réinitialiser le mot de passe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../Asset/traitement/mdp.php" method="post">
                    <div class="modal-body">
                        <label for="passwordA">Mot de passe actuel</label>
                        <input type="password" name="passwordA" id="passwordA" class="form-control">
                        <input type="hidden" name="hid" value="<?php echo htmlspecialchars($id_p); ?>">

                        <label for="passwordN">Nouveau mot de passe</label>
                        <input type="password" name="passwordN" id="passwordN" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <input type="submit" class="btn btn-primary" value="Modifier" name="send">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal pour changer la photo de profil -->
    <div class="modal fade" id="changePhotoModal" tabindex="-1" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePhotoModalLabel">Changer de photo de profil</h5>
                    <button type="button" class="btn-close" onclick="closeModal('changePhotoModal')" aria-label="Close"></button>
                </div>
                <form action="../Asset/traitement/uppp.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body text-center">
                        <img id="previewImage" src="<?php echo '../images/pp_users/' . htmlspecialchars($rows['pp']); ?>" alt="Profil" class="img-thumbnail mb-3" style="width: 150px; height: 150px;">
                        <label for="pp2" class="btn btn-primary"><i class="fas fa-camera"></i> Changer</label>
                        <input type="file" name="pp2" id="pp2" style="display: none;" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <input type="hidden" name="hid" value="<?php echo htmlspecialchars($id_p); ?>">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('changePhotoModal')">Annuler</button>
                        <input type="submit" class="btn btn-primary" value="Modifier" name="send">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for adding a new user -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Ajouter un utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../Asset/traitement/adduser.php" method="post">
                    <div class="modal-body">
                        <label for="newUserName">Nom</label>
                        <input type="text" name="name" id="newUserName" class="form-control" required>

                        <label for="newUserEmail">Email</label>
                        <input type="email" name="email" id="newUserEmail" class="form-control" required>

                        <label for="newUserPassword">Mot de passe</label>
                        <input type="password" name="password" id="newUserPassword" class="form-control" required>

                        <label for="newUserRole">Rôle</label>
                        <select name="role" id="newUserRole" class="form-control" required>
                            <option value="admin">Administrateur</option>
                            <option value="editor">Professeur</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <input type="submit" name="send" class="btn btn-primary" value="Ajouter">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            const preview = document.getElementById('previewImage');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = "<?php echo '../images/pp_users/' . htmlspecialchars($rows['pp']); ?>";
            }
        }

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'block';
            modal.removeAttribute('aria-hidden');
            document.body.setAttribute('inert', 'true');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            document.body.removeAttribute('inert');
        }
    </script>
</body>

</html>
<?php } else {
    header("Location: login.php");
} ?>