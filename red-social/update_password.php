<?php

require "config.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    $password = $_POST["password"] ?? "";
    $password_confirm = $_POST["password_confirm"] ?? "";

    $password_valid = preg_match("/^\s*$/", $password) || preg_match("/<\/?[^>]+>/", $password);
    $password_confirm_valid = preg_match("/^\s*$/", $password_confirm) || preg_match("/<\/?[^>]+>/", $password_confirm);

    if ($password_valid || $password_confirm_valid){
        echo "No cumple las validaciones";
        header('Location:' . getenv('HTTP_REFERER'));
        exit;
    }
    
    if ($password == $password_confirm){

        $id = $_SESSION["email"];
        
        $password = password_hash($password,PASSWORD_DEFAULT);
        $sql = $conn->prepare("UPDATE usuarios set password = ? where id = ?");
        $sql->bind_param("si",$password,$id);
        if ($sql->execute()){

            $_SESSION["password_changed"] = true;
            header("Location: login.php");
            
        }

    } else header('Location:' . getenv('HTTP_REFERER'));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  <link rel="stylesheet" href="styles/style.css">

</head>

<body>

    <div class="container">
        <form class="form-body" action="" method="post">
            <div class="input">
                <input type="text" required name="password" id="">
            </div>
            <div class="input">
                <input type="text" required name="password_confirm" id="">
            </div>

            <div class="form-btn">
                <input type="submit" name="" id="">                
            </div>
        </form>
    </div>

</body>

</html>