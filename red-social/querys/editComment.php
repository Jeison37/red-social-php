<?php

require "../validation.php";

$id = $_POST["id"];
$content = $_POST["content"];
$id_user = $_SESSION["id"];
$rol = $_SESSION["rol"];

if ($res = $conn->query("UPDATE  comentarios set contenido ='$content'  where id = $id and (id_usuario = $id_user or $rol = 1)")){
    header('Location:' . getenv('HTTP_REFERER'));
} else echo $conn->error;