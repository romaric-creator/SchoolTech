<?php
include "config.php";
session_start();

if (isset($_GET['query'])) {
    $query = htmlspecialchars(trim($_GET['query']));
    $sql = "SELECT * FROM reservation WHERE nom_us LIKE '%$query%' OR nom_salle LIKE '%$query%' ORDER BY id_reservation DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
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
    } else {
        echo '<tr><td colspan="9" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">Aucune réservation trouvée.</td></tr>';
    }
} else {
    $_SESSION['error_message'] = "Requête invalide.";
    header("Location: ../../php/reservation.php");
    exit();
}
?>