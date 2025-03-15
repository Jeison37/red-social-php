<?php

require "email/email.php";

require "config.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = $_POST["email"] ?? "";

    $correo_valid = preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $correo);

    if (!$correo_valid) {
        echo "No cumple las validaciones";
        header('Location:' . getenv('HTTP_REFERER'));
        exit;
    }

    if ($res = $conn->query("SELECT * from usuarios where correo_electronico = '$correo'")) {
        
        $user = $res->fetch_assoc();
        $email = $user["correo_electronico"];
        $_SESSION["email"] = $user["id"];
        $code = random_int(100000, 999999);
        echo $code;
        // changePassword($code, $email);
        $code = password_hash($code, PASSWORD_DEFAULT);

    } else {
        header("Location: search_email.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar la contraseña</title>
  <link rel="stylesheet" href="styles/style.css">

</head>

<body>

    <div class="container">
        <form action="check_code.php" method="post">
            <div class="input">
                <input type="number" name="code" id="" placeholder="Introduce el codigo">

            </div>
            <input type="hidden" name="hashed_code" id="" value="<?php echo $code; ?>">

            <div class="form-btn">
                <input type="submit"  name="" id="" >               
            </div>
        </form>
    </div>

</body>

</html>