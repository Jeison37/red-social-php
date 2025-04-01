<?php

require "config.php";

session_start();
if (!empty($_SESSION["id"])) {
  header("Location: main.php");
  exit;
}

if (isset($_SESSION["password_changed"])) {
  if ($_SESSION["password_changed"] == "true") {

    echo "<script language='JavaScript'>

    alert('Contraseña actualizada exitosamente');

</script>";

    unset($_SESSION["password_changed"]);

  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $correo = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
  $clave = $_POST["password"] ?? "";

  $correo_valid = preg_match("/^\s*$/", $correo) || preg_match("/<\/?[^>]+>/", $correo);

  $clave_valid = $clave_valid = preg_match("/^\s*$/", $clave) || preg_match("/<\/?[^>]+>/", $clave) || preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $clave);

  if ($clave_valid ||  $correo_valid) {
    echo "No cumple las validaciones";
    header('Location:' . getenv('HTTP_REFERER'));
    exit;
  }

  $sql = $conn->prepare("SELECT id, password, rol FROM usuarios WHERE correo_electronico = ?");
  $sql->bind_param("s", $correo);
  $sql->execute();
  $sql->store_result();

  if ($sql->num_rows === 1) {
    $sql->bind_result($id, $hashedPassword, $rol);
    $sql->fetch();
    if (password_verify($clave, $hashedPassword)) {
      session_regenerate_id(true);
      $_SESSION["login"] = true;
      $_SESSION["id"] = $id;
      $_SESSION["rol"] = $rol;
      header("Location: main.php");
      exit;
    } else {
      echo "Clave invalida $hashedPassword ";
      echo password_hash($clave, PASSWORD_DEFAULT);
    }
  }
  $sql->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión</title>
  <link rel="stylesheet" href="styles/style.css">
</head>

<body>

<div class="password_modal" hidden>
  Contraseña cambiada exitosamente
</div>

  <div class="container">

    <div class="login-container">
      <div class="login-box">
        <div class="form-head">
          <div class="form-logo">
            <img src="assets/logo.png" alt="">
          </div>
        </div>
        <form class="form-body" action="login.php" class="" method="post">
          <div class="input">
            <input type="email" placeholder="Correo electrónico" name="email" id="email">
          </div class="input">
          <div class="input">
            <input type="password" placeholder="Contraseña" name="password" id="password">
          </div class="input">
          <div class="form-btn">
            <input type="submit" value="Entrar">
          </div class="input">
          <a class="password" href="search_email.php">¿Olvidaste tu contraseña?</a>
        </form>
      </div>

      <div class="register-box">
        <span>
          <p>
            ¿No tienes cuenta una cuenta?
            <a href="register.php">Regístrate</a>
          </p>
        </span>
      </div>
    </div>


  </div>

</body>

</html>