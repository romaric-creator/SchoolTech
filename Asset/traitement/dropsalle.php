<?php
include 'config.php';
    if(isset($_GET['delete'])){
        $id_s = $_GET['delete'];
        $sqlse = "DELETE FROM salle WHERE id_salle = '$id_s'";
        $resea = mysqli_query($conn,$sqlse);
            if(!$resea){
            ?>
            <script>
                alert('imposible de suprimer la salle');
            </script>
            <?php
            }else{
                header("Location: ../../php/maintenance.php");
            }
    }
?>