<?php

require "../validation.php";

$id = $_GET["post"];

$id_user = $_SESSION["id"];

if ($res = $conn->query("DELETE from comentarios where id = $id and id_usuario = $id_user")){
    header('Location:' . getenv('HTTP_REFERER'));
} else echo $conn->error;