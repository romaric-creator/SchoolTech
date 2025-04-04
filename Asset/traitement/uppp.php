<?php 
include "config.php";
    if (isset($_POST['send'])) {
        if (isset($_POST['hid'])) {
            $id = $_POST['hid'];
        }
        if (isset($_FILES['pp2'])) {
            $pp_name = $_FILES['pp2']['name'];
            $pp_tmpname = $_FILES['pp2']['tmp_name'];
        }
        if($id == "" || $pp_name == ""){
            $error = "renseignez tous les champs";
            header("Location: ../../php/users.php?error=$error#pp");
        }else{
               function securate($data){
                    $data = htmlspecialchars(trim($data));;
                    return $data;
               }
            $id = securate($id);
            $pp_name = securate($pp_name);
            if(move_uploaded_file($pp_tmpname,"../../Images/pp_users/".$pp_name)){
                $sql = "UPDATE users SET pp = '$pp_name' WHERE id_users = '$id'";
                    $res = mysqli_query($conn,$sql);
                if ($res) {
                    header("location: ../../php/users.php");
                }else{
                    $error = "erreur lors de l'enregistrement";
                    header("Location: ../../php/users.php?error=$error#pp");
                }
            }

             }

    }
?>