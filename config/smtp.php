<?php
require_once __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../vendor/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($to, $template,$subject,$data = []) {
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'web@repuestoscampos.store';
        $mail->Password = '|5Vfg6D&r';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
  
        $mail->setFrom('web@repuestoscampos.store', 'Repuestos Campos');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
 
        ob_start();
        extract($data); 
        include __DIR__ . '/../app/views/mail/' . $template.'.php';
        $body = ob_get_clean();

        $mail->Body = $body;
        $mail->AltBody = $data['altBody'] ?? strip_tags($body);

        $mail->send();
        return true;


    } catch (Exception $e) {
        return false;
    }
}
