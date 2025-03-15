<?php

require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $code = $_POST["code"] ?? "";

    $hashed_code = $_POST["hashed_code"] ?? "a";

    $code_valid = preg_match("/^\s*$/", $code) || preg_match("/<\/?[^>]+>/", $code);

    if ($code_valid || !$hashed_code) {
        echo "No cumple las validaciones";
        header('Location:' . getenv('HTTP_REFERER'));
        exit;
    }
    
    // var_dump([$code,$hashed_code]);
    // echo $hashed_code.": hola";
    // echo $code;

    if (password_verify($code, $hashed_code)) {

        header("Location: update_password.php");

    } else {
        header("Location: password.php");
    }
}
