<?php
require_once 'config.php'; // Adjust the path as necessary

if (isset($_SESSION['id_users'])) {
    $stmt ="SELECT status FROM users WHERE id_users = $_SESSION[id_users]";
    $stmt2=mysqli_query($conn,$stmt);
    $userRole =mysqli_fetch_assoc($stmt2);
} else {
    $userRole = null;
}
if (isset($_SESSION['success_message'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('" . addslashes($_SESSION['success_message']) . "', 'success');
        });
    </script>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('" . addslashes($_SESSION['error_message']) . "', 'error');
        });
    </script>";
    unset($_SESSION['error_message']);
}

?>
<script>
     function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-500 transform translate-y-0 opacity-0 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                    <p>${message}</p>
                </div>
            `;
            document.body.appendChild(toast);
            
            // Animation d'entrée
            setTimeout(() => {
                toast.classList.remove('opacity-0');
                toast.classList.add('opacity-100');
            }, 10);
            
            // Animation de sortie
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-[-20px]');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }, 5000);
        }
        
</script>
<div id="sidebar" class="bg-white w-64 fixed h-full shadow-lg z-20 transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-center">
                <img src="../Images/IA.PNG" alt="Logo" class="h-10 mr-2">
                <span class="text-xl font-semibold text-primary">SchoolITech</span>
            </div>
        </div>
        
        <nav class="mt-5">
            <div class="px-4 py-2 text-xs text-gray-500 uppercase">Menu principal</div>
            <?php if ($userRole['status'] === 'admin'):  ?>
                
                <a href="home.php" class="block px-4 py-2 text-sm text-gray hover:bg-gray-100 transition-colors">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="maintenance.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-door-open mr-2"></i> Salles
                </a>
            <?php endif; ?>
            <a href="reservation.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <i class="fas fa-calendar-check mr-2"></i> Réservations
            </a>
            <a href="service.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <i class="fas fa-wrench mr-2"></i> Services
            </a>
            <a href="help.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <i class="fas fa-circle-question mr-2"></i> Aide
            </a>
            <a href="notification.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <i class="fas fa-bell mr-2"></i> Notifications
                <?php if (@$numnot > 0) { ?>
                    <span class="ml-1 px-2 py-0.5 bg-red-500 text-white rounded-full text-xs"><?php echo $numnot; ?></span>
                <?php } ?>
            </a>
            
            <div class="px-4 py-2 mt-4 text-xs text-gray-500 uppercase">Administration</div>
            <a href="users.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <i class="fas fa-cog mr-2"></i> Paramètres
            </a>
            <a href="logout.php" class="block px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">
                <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
            </a>
        </nav>
        
        <div class="absolute bottom-0 w-full border-t border-gray-200 p-4">
            <div class="text-center text-xs text-gray-500">
                &copy; <?php echo date('Y'); ?> SchoolITech
                <div class="mt-1">Version 1.0</div>
            </div>
        </div>
    </div>