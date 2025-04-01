<?php
require "config.php";
require "email/email.php";

session_start();
if (!empty($_SESSION["id"])) {
  header("Location: main.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $correo = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
  $clave = $_POST["password"] ?? "";
  $username = $_POST["username"] ?? "";

  $username_valid = preg_match("/^\s*$/", $correo) || preg_match("/<\/?[^>]+>/", $clave);

  $correo_valid = preg_match("/^\s*$/", $correo) || preg_match("/<\/?[^>]+>/", $clave);

  $clave_valid = preg_match("/^\s*$/", $clave) || preg_match("/<\/?[^>]+>/", $clave);

  if ($clave_valid ||  $correo_valid || $username_valid) {
    echo "No cumple las validaciones";
    header('Location:' . getenv('HTTP_REFERER'));
    exit;
  }

  $hash = password_hash($clave, PASSWORD_DEFAULT);
  $rol = 0;
  $sql = $conn->prepare("INSERT INTO usuarios (nombre_usuario,correo_electronico,password,rol) VALUES (?,?,?,?)");
  $sql->bind_param("sssi", $username, $correo, $hash, $rol);

  welcome($correo);
  if ($sql->execute()) {
    session_regenerate_id(true);
    $_SESSION["login"] = true;
    $id = $conn->insert_id;
    $_SESSION["id"] = $id;
    $_SESSION["rol"] = $rol;
    
    header("Location: main.php");
    exit;
  }
  $sql->close();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrarse</title>
  <link rel="stylesheet" href="styles/style.css">
</head>

<body>

  <div class="container">

    <div class="login-container">
      <div class="login-box">
        <div class="form-head">
          <div class="form-logo">
            <img src="assets/logo.png" alt="">
          </div>
        </div>
        <form class="form-body" action="register.php" method="post">
          <div class="input">
            <input type="email" placeholder="Correo electrónico" name="email" id="email">
          </div class="input">
          <div class="input">
            <input type="username" name="username" placeholder="Nombre de usuario" id="username">
          </div class="input">
          <div class="input">
            <input type="password" placeholder="Contraseña" name="password" id="password">
          </div class="input">
          <div class="form-btn">
            <input type="submit" value="Registrarte">
          </div class="input">
          <a class="password" href="search_email.php">¿Olvidaste tu contraseña?</a>
        </form>
      </div>

      <div class="register-box">
        <span>
          <p>
            
            <a href="login.php">Inicia sesión</a>
          </p>
        </span>
      </div>
    </div>


  </div>

</body>

</html>