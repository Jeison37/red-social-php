<?php

include "config.php";

session_start();
if (empty($_SESSION["id"])) {
    header("location: login.php");
    exit;
}

$id = $_SESSION["id"];


if ($res = $conn->query("SELECT * from usuarios where id = $id")) {
    $user = $res->fetch_assoc();
    $user_rol = $user["rol"];
} else echo "Error: $conn->error";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contenido = $_POST["post"] ?? "";

    $contenido_valid = preg_match("/^\s*$/", $contenido) || preg_match("/<\/?[^>]+>/", $contenido);

    if ($contenido_valid) {
        echo "No cumple las validaciones";
        header('Location:' . getenv('HTTP_REFERER'));
        exit;
    }
    $sql = $conn->prepare("INSERT INTO publicaciones (id_usuario,contenido) VALUES (?,?)");
    $sql->bind_param("is", $id, $contenido);
    $sql->execute();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Y</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>

    <dialog id="edit_window">
        <form action="querys/editPost.php" method="post">

            <input type="text" name="content" id="content">
            <input type="hidden" name="id" id="id">

            <input type="submit" name="" id="" value="Editar">
            <input type="submit" formmethod="dialog" value="Cancelar" id="">

        </form>
    </dialog>

    <main class="container">

        <div class="left-side">

        <div class="logo">
            <img src="assets/logo.png" alt="">
        </div>
            
        </div>

        <div class="scroll">
            <div class="post-column">
            
                <div class="post_form">
                    <form action="main.php" method="post">
                        <input type="text" required class="post_inp" placeholder="¿Qué esta pasando?" name="post" id="">
                        <input type="submit" class="post-btn" value="Publicar">
                    </form>
                </div>
                <?php
                $query = "SELECT publicaciones.id, publicaciones.id_usuario, publicaciones.contenido, publicaciones.fecha_creacion, usuarios.rol, usuarios.nombre_usuario, usuarios.correo_electronico from publicaciones inner join usuarios on usuarios.id = publicaciones.id_usuario";
                if ($res = $conn->query($query)) {
                    while ($post = $res->fetch_assoc()) {
                        $id_post = $post["id"];
                        $post_user = $post["id_usuario"];
                        $post_contenido = $post["contenido"];
                        $post_fecha_creacion = $post["fecha_creacion"];
                        $post_nombre_usuario = $post["nombre_usuario"];
                        $post_correo_electronico = $post["correo_electronico"];
                        $res_user_like = $conn->query("SELECT * from likes where id_publicacion = $id_post and id_usuario = $id");
                        $is_liked = $res_user_like->num_rows > 0 ? "liked" : "";

                        $res_likes = $conn->query("SELECT * from likes where id_publicacion = $id_post");
                        $likes_n = $res_likes->num_rows;
                ?>
                        <div class="post_container">
                            <!-- <button class="details-btn"></button> -->
                            <div class="post_user">
                                <div class="user_post_data">
                                    <span class="username">
                                        <?php echo $post_nombre_usuario ?>
                                    </span>
                                    <span class="email">
                                        <?php echo $post_correo_electronico ?>
                                    </span>
                                </div>
                                <div class="contenido"><?php echo $post_contenido ?></div>
                                <?php
                                if ($post_user == $id || $user_rol == 1) {
                                ?>
                                    <button class="edit" data-edit="<?php echo $id_post ?>">Editar</button>
                                <?php } ?>
                                <?php
                                if ($post_user == $id || $user_rol == 1) {
                                ?>
                                    <a href="querys/deletePost.php?post=<?php echo $id_post ?>">
                                        Eliminar
                                    </a>
                                <?php } ?>
                                <div class="actions">
                                    <button class="comment" data-post="<?php echo $id_post ?>">
                                        <svg  viewBox="0 0 24 24" aria-hidden="true" f class="comment_icon">
                                            <g>
                                                <path d="M1.751 10c0-4.42 3.584-8 8.005-8h4.366c4.49 0 8.129 3.64 8.129 8.13 0 2.96-1.607 5.68-4.196 7.11l-8.054 4.46v-3.69h-.067c-4.49.1-8.183-3.51-8.183-8.01zm8.005-6c-3.317 0-6.005 2.69-6.005 6 0 3.37 2.77 6.08 6.138 6.01l.351-.01h1.761v2.3l5.087-2.81c1.951-1.08 3.163-3.13 3.163-5.36 0-3.39-2.744-6.13-6.129-6.13H9.756z"></path>
                                            </g>
                                        </svg>
                                    <?php
                                    $res_comments = $conn->query("SELECT * from comentarios where id_publicacion = $id_post");
                                    echo $res_comments->num_rows;
                                    ?>
                                    </button>
                                    <button class="like <?php echo $is_liked ?>" data-post="<?php echo $id_post ?>">
                                        <svg viewBox="0 0 24 24" aria-hidden="true" class="heart_icon">
                                            <g>
                                                <path d="M16.697 5.5c-1.222-.06-2.679.51-3.89 2.16l-.805 1.09-.806-1.09C9.984 6.01 8.526 5.44 7.304 5.5c-1.243.07-2.349.78-2.91 1.91-.552 1.12-.633 2.78.479 4.82 1.074 1.97 3.257 4.27 7.129 6.61 3.87-2.34 6.052-4.64 7.126-6.61 1.111-2.04 1.03-3.7.477-4.82-.561-1.13-1.666-1.84-2.908-1.91zm4.187 7.69c-1.351 2.48-4.001 5.12-8.379 7.67l-.503.3-.504-.3c-4.379-2.55-7.029-5.19-8.382-7.67-1.36-2.5-1.41-4.86-.514-6.67.887-1.79 2.647-2.91 4.601-3.01 1.651-.09 3.368.56 4.798 2.01 1.429-1.45 3.146-2.1 4.796-2.01 1.954.1 3.714 1.22 4.601 3.01.896 1.81.846 4.17-.514 6.67z"></path>
                                            </g>
                                        </svg>
                                        
                                        
                                        <?php

                                    echo "<span class='current-n'>$likes_n</span>";
                                    $like_state = $is_liked ? $likes_n - 1 : $likes_n + 1 ;
                                    echo "<span class='dif-n hidden'>$like_state</span>";
                                    
                                    ?>
                                    </button> 
                                    
                                    
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                $conn->close();
                ?>
        </div>
        </div>


    </main>
    <script src="actionListeners.js"></script>
</body>

</html>