<?php

namespace App;

use App\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail {

    public static function send($to, $subject, $text, $html) {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP(); 
            $mail->Host       = 'smtp.mailtrap.io';
            $mail->SMTPAuth   = true; 
            $mail->Username   = Config::PHP_Mailer_Username;
            $mail->Password   = Config::PHP_Mailer_Password; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 25;
        
            //Recipients
            $mail->setFrom('login-mvc@admin.com', 'Mailer');   
            $mail->addAddress($to);          

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->AltBody = $text;
        
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}