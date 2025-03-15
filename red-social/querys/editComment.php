<?php

require "../validation.php";

$id = $_POST["id"];
$content = $_POST["content"];
$id_user = $_SESSION["id"];


if ($res = $conn->query("UPDATE  comentarios set contenido ='$content'  where id = $id and id_publicaion = $id_user")){
    header('Location:' . getenv('HTTP_REFERER'));
} else echo $conn->error;