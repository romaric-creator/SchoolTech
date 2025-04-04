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

    include "../Asset/traitement/config.php";

    session_start();
    if (!isset($_SESSION['id_users'])) {
        header("Location: login.php");
        exit();
    }

    $id_p = $_SESSION['id_users'];

    $sql_user = "SELECT pp FROM users WHERE id_users = '$id_p'";
    $res_user = mysqli_query($conn, $sql_user);
    $rows = mysqli_fetch_assoc($res_user);

    // Récupérer les notifications non lues
    $sqlnot_reservation_unread = "SELECT * FROM reservation WHERE status = 'on'";
    $resnot_reservation_unread = mysqli_query($conn, $sqlnot_reservation_unread);

    $sqlnot_service_unread = "SELECT * FROM service WHERE status = 'nouveau'";
    $resnot_service_unread = mysqli_query($conn, $sqlnot_service_unread);

    // Récupérer les notifications déjà lues
    $sqlnot_reservation_read = "SELECT * FROM reservation WHERE status = 'off'";
    $resnot_reservation_read = mysqli_query($conn, $sqlnot_reservation_read);

    $sqlnot_service_read = "SELECT * FROM service WHERE status = 'off'";
    $resnot_service_read = mysqli_query($conn, $sqlnot_service_read);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_as_read'])) {
        $notification_id = $_POST['notification_id'];
        $notification_type = $_POST['notification_type'];

        if ($notification_type === 'reservation') {
            $update_query = "UPDATE reservation SET status = 'off' WHERE id_reservation = '$notification_id'";
        } elseif ($notification_type === 'service') {
            $update_query = "UPDATE service SET status = 'off' WHERE id_service = '$notification_id'";
        }

        if (mysqli_query($conn, $update_query)) {
            $_SESSION['success_message'] = "Notification marked as read successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to mark notification as read.";
        }
        header("Location: notification.php");
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="../Css/style.css">
        <link rel="stylesheet" href="../Css/notification.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
        <title>Notifications</title>
        <style>
            /* Global styles */
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f6f9;
                color: #333;
            }

            /* Header styles */
            .header {
                background-color: #007bff;
                color: #fff;
                padding: 5px 30px;
                display: flex;
                justify-content: space-between;
                align-items: center;
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
                font-size: 1.8rem;
                position: relative;
                cursor: pointer;
                color: #fff;
                transition: transform 0.3s ease;
            }

            .header .menu_us .icon-bell:hover {
                transform: scale(1.1);
            }

            .header .menu_us .icon-bell .num {
                position: absolute;
                top: -8px;
                right: -10px;
                background-color: #dc3545;
                color: #fff;
                font-size: 0.8rem;
                font-weight: bold;
                padding: 3px 7px;
                border-radius: 50%;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            .header .menu_us img {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                border: 2px solid #fff;
                object-fit: cover;
            }

            /* Main content */
            .main-content {
                margin-left: 280px;
                padding: 20px;
            }

            .notifications {
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 20px;
            }

            .notifications h1 {
                font-size: 1.5rem;
                font-weight: bold;
                color: #333;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .notification-item {
                display: flex;
                align-items: flex-start;
                gap: 15px;
                padding: 15px;
                border-bottom: 1px solid #f0f0f0;
                transition: background-color 0.3s;
            }

            .notification-item:last-child {
                border-bottom: none;
            }

            .notification-item:hover {
                background-color: #f9f9f9;
            }

            .notification-item .icon {
                font-size: 2rem;
                color: #007bff;
                flex-shrink: 0;
            }

            .notification-item .content {
                flex-grow: 1;
            }

            .notification-item .content p {
                margin: 0;
                font-size: 1rem;
                color: #555;
            }

            .notification-item .content p strong {
                color: #333;
            }

            .notification-item .content .date {
                font-size: 0.9rem;
                color: #999;
                margin-top: 5px;
            }

            .notification-item.empty {
                text-align: center;
                color: #999;
                font-size: 1rem;
            }

            .btn-mark-read {
                background-color: #007bff;
                color: #fff;
                border: none;
                padding: 5px 10px;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .btn-mark-read:hover {
                background-color: #0056b3;
            }

            .alert {
                padding: 10px;
                margin-bottom: 20px;
                border-radius: 5px;
                font-size: 1rem;
            }

            .alert-success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }

            .alert-danger {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
        </style>
    </head>
    <body class="bg-gray-100 font-sans">
    <?php include '../Asset/traitement/sidebar.php'; ?>

        <!-- Header -->
        <div class="header bg-primary shadow-md md:ml-64 transition-all">
    <div></div>
            <div class="menu_us">
                <a href="notification.php" class="icon-bell">
                    <i class="fas fa-bell"></i>
                    <?php 
                    $total_notifications = mysqli_num_rows($resnot_reservation_unread) + mysqli_num_rows($resnot_service_unread);
                    if ($total_notifications > 0) {
                        echo '<span class="num">' . $total_notifications . '</span>';
                    }
                    ?>
                </a>
                <img src="<?php echo '../images/pp_users/' . (!empty($rows['pp']) ? $rows['pp'] : 'default.png'); ?>" alt="User Profile">
            </div>
        </div>

        <!-- Sidebar -->

        <!-- Main content -->
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
            <div class="notifications">
                <h1><i class="fas fa-bell"></i> Notifications non lues</h1>
                <?php 
                if ($total_notifications == 0) {
                    echo '<div class="notification-item empty">
                            <i class="fas fa-info-circle"></i>
                            <p>Aucune notification non lue.</p>
                        </div>';
                } else {
                    // Notifications non lues des réservations
                    while ($row_reservation = mysqli_fetch_assoc($resnot_reservation_unread)) {
                        echo '
                        <div class="notification-item">
                            <div class="icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="content">
                                <p><strong>Nouvelle réservation</strong></p>
                                <p>Mr ' . htmlspecialchars($row_reservation['nom_us']) . ' a réservé une salle le ' . htmlspecialchars($row_reservation['date_res']) . ' de ' . htmlspecialchars($row_reservation['debh']) . ' à ' . htmlspecialchars($row_reservation['debf']) . '.</p>
                                <p class="date">Date : ' . htmlspecialchars($row_reservation['date_res']) . '</p>
                                <form method="POST" style="margin-top: 10px;">
                                    <input type="hidden" name="notification_id" value="' . htmlspecialchars($row_reservation['id_reservation']) . '">
                                    <input type="hidden" name="notification_type" value="reservation">
                                    <button type="submit" name="mark_as_read" class="btn-mark-read">Marquer comme lu</button>
                                </form>
                            </div>
                        </div>';
                    }

                    // Notifications non lues des services
                    while ($row_service = mysqli_fetch_assoc($resnot_service_unread)) {
                        echo '
                        <div class="notification-item">
                            <div class="icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="content">
                                <p><strong>Nouveau signalement</strong></p>
                                <p>Mr ' . htmlspecialchars($row_service['nom_us']) . ' a signalé un problème : ' . htmlspecialchars($row_service['contenu']) . '.</p>
                                <p class="date">Date : ' . htmlspecialchars($row_service['date_service']) . '</p>
                                <form method="POST" style="margin-top: 10px;">
                                    <input type="hidden" name="notification_id" value="' . htmlspecialchars($row_service['id_service']) . '">
                                    <input type="hidden" name="notification_type" value="service">
                                    <button type="submit" name="mark_as_read" class="btn-mark-read">Marquer comme lu</button>
                                </form>
                            </div>
                        </div>';
                    }
                }
                ?>

                <h1><i class="fas fa-history"></i> Notifications déjà lues</h1>
                <?php 
                // Notifications déjà lues des réservations
                while ($row_reservation = mysqli_fetch_assoc($resnot_reservation_read)) {
                    echo '
                    <div class="notification-item">
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="content">
                            <p><strong>Réservation</strong></p>
                            <p>Mr ' . htmlspecialchars($row_reservation['nom_us']) . ' a réservé une salle le ' . htmlspecialchars($row_reservation['date_res']) . ' de ' . htmlspecialchars($row_reservation['debh']) . ' à ' . htmlspecialchars($row_reservation['debf']) . '.</p>
                            <p class="date">Date : ' . htmlspecialchars($row_reservation['date_res']) . '</p>
                        </div>
                    </div>';
                }

                // Notifications déjà lues des services
                while ($row_service = mysqli_fetch_assoc($resnot_service_read)) {
                    echo '
                    <div class="notification-item">
                        <div class="icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="content">
                            <p><strong>Signalement</strong></p>
                            <p>Mr ' . htmlspecialchars($row_service['nom_us']) . ' a signalé un problème : ' . htmlspecialchars($row_service['contenu']) . '.</p>
                            <p class="date">Date : ' . htmlspecialchars($row_service['date_service']) . '</p>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
        <script>
            // Marquer les notifications comme lues lorsque l'utilisateur quitte la page
            window.addEventListener("beforeunload", function () {
                // Effectuer une requête AJAX pour marquer les notifications comme lues
                navigator.sendBeacon("mark_notifications_read.php");
            });
        </script>
    </body>
    </html>