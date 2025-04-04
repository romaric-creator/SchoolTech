<nav class="box-bar">
    <ul>
        <li>
            <a href="home.php">
                <div class="logo">
 
                    <img src="Images/IA.PNG" alt="error">
                    <p>my dashboard</p>

                </div>
            </a>
        </li>
        <li>
            <span class="search_bar">
                <form action="search.php" method="get">
                    <input type="search" name="search" class="search" placeholder="faites vos recherche ici">
                </form>
            </span>
        </li>
        <li>
            <?php 
                        include "Asset/traitement/config.php";
                        $sqlnot = "SELECT * FROM reservation WHERE status = 'on' ";
                        $resnot = mysqli_query($conn,$sqlnot);
                        $numnot = mysqli_num_rows($resnot);
                    ?>
            <div class="menu_us">
                <span class="icon-align-justify2" id="btn"></span>
                <a href="notification.php?vue"><span class="icon-bell" id="<?php if($numnot > 0){echo " noton";}else{
                        echo "notof" ; } ?>">

                        <?php if($numnot > 0){echo '<div class="num"><p>'.$numnot.'</p></div>';}else{ echo ' ';} ?>
                    </span></a>
                <span class="icon-user"></span>
            </div>
        </li>
    </ul>
</nav>