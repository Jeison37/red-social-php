<?php

require "../validation.php";

$id = $_GET["post"];
$id_user = $_SESSION["id"];


if ($res = $conn->query("DELETE from publicaciones where id = $id and id_publicaion = $id_user")){
    header('Location:' . getenv('HTTP_REFERER'));
} else echo $conn->error;