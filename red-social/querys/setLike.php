<?php

require "../validation.php";

$id_post = $_GET["id_post"];
$id_user = $_SESSION["id"];

$res = $conn->query("SELECT * from likes where id_publicacion = $id_post and id_usuario = $id_user");
if ($res->num_rows == 1){
    $conn->query("DELETE from likes where id_publicacion = $id_post and id_usuario = $id_user");
    $data = json_encode(["msg"=>"Peticion de like exitosa","like"=>false]);
} else{
    // echo $id_user;
    $conn->query("INSERT into likes (id_publicacion, id_usuario) values ($id_post, $id_user)");
    $data = json_encode(["msg"=>"Peticion de like exitosa","like"=>true]);
}
header('Content-Type: application/json');

echo $data;
exit;

// Warning: Undefined array key "post" in C:\xampp\htdocs\red-social\post.php on line 13
// hola que pasa
// Fatal error: Uncaught mysqli_sql_exception: Duplicate entry '0' for key 'PRIMARY' in C:\xampp\htdocs\red-social\post.php:20 Stack trace: #0 C:\xampp\htdocs\red-social\post.php(20): mysqli_stmt->execute() #1 {main} thrown in C:\xampp\htdocs\red-social\post.php on line 20