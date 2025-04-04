<?php 
        $host="localhost";
        $user="root";
        $password="";
        $base="my dashboard";
    
        $conn = mysqli_connect($host,$user,$password);
        $sqls = mysqli_select_db($conn,$base);
        if($sqls){
            
        }else{
            header("Location: install.php#t1");
        }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Outils Html/icomoon/style.css">
    <link rel="stylesheet" href="../Css/login.css">
    <title> login</title>
    <style>
        /* Global styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #007bff, #6c757d);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .contlog {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .form h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        .form .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }

        .form input[type="email"],
        .form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .form input[type="submit"]:hover {
            background: #0056b3;
        }

        .form input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .form a {
            display: block;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #007bff;
            text-decoration: none;
        }

        .form a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body  class="bg-gray-100 font-sans">
    <div class="contlog">
        <div class="form">
            <?php if(isset($_GET['error'])){?>
                <p class="error"><span class="icon-warning"></span> <?php echo $_GET['error'] ; ?></p>
            <?php } ?>
            <h1>my dashboard</h1>
            <form action="../Asset/traitement/login.php" method="post">
                <input type="email" name="email" placeholder="email" autocomplete="off">
                <input type="password" name="password" placeholder="password" autocapitalize="off">
                <input type="submit" value="connecter" name="send">
            </form>
        </div>
    </div>
</body>

</html>