<?php

require "../validation.php";

$id = $_GET["post"];
$id_user = $_SESSION["id"];
$rol = $_SESSION["rol"];


if ($res = $conn->query("DELETE from publicaciones where id = $id and (id_usuario = $id_user or $rol = 1)")){
    header('Location:' . getenv('HTTP_REFERER'));
} else echo $conn->error;