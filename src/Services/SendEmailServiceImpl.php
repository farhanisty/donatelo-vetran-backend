<?php

namespace Farhanisty\DonateloBackend\Services;

use PHPMailer\PHPMailer\PHPMailer;

class SendEmailServiceImpl implements SendEmailService
{
    public function send(string $address, string $body, string $imagePath): bool
    {
        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_DOMAIN'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('no-reply@donatelo.com');
            $mail->addAddress($address);

            $mail->isHtml(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Thank You for Your Purchase at Donatelo! 🍩';
            $mail->Body = $body;
            $mail->addAttachment($imagePath);

            $mail->send();

            $mail->SMTPDebug = 2;

            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
