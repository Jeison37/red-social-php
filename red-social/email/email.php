<?php

use PHPMailer\PHPMailer\PHPMailer;

require 'phpmailer/src/EXception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
function welcome($email){
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = "";
    $mail->Password = '';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    
    $mail->setFrom('');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Bienvenida";
    $mail->Body = "<h1>Bienvenido/Bienvenida a la red social y.com</h1>";
    $mail->send();
}

function changePassword($code,$email){
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = "";
    $mail->Password = '';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    
    $mail->setFrom("");
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Cambio de clave";
    $mail->Body = "<h3>Para cambiar tu contraseña escribe el siguiente codigo: $code</h3>";
    $mail->send();
}
