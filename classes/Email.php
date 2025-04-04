<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Email {
    private $email;
    private $nombre;
    private $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.sendgrid.net'; // Servidor SMTP de SendGrid
            $mail->SMTPAuth = true;
            $mail->Username = 'apikey'; // Usuario SMTP (literalmente "apikey")
            $mail->Password = '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'; // Tu clave API de SendGrid
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Cifrado TLS
            $mail->Port = 587; // Puerto recomendado para TLS

            // Remitente y destinatario
            $mail->setFrom('juanmartinez@nusoft.com.co', 'Soporte educa.city'); // Cambia al email que usarás como remitente
            $mail->addAddress($this->email, $this->nombre);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Confirma tu cuenta';
            $mail->Body = '<p><strong>Hola ' . $this->nombre . '</strong>, confirma tu cuenta haciendo clic en el siguiente enlace:</p>' .
                '<p><a href="http://localhost:3000/confirmar-cuenta?token=' . $this->token . '">Confirmar Cuenta</a></p>';

            // Enviar el correo
            $mail->send();
            echo 'Correo enviado correctamente.';
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    }
    public function enviarInstrucciones() {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.sendgrid.net'; // Servidor SMTP de SendGrid
            $mail->SMTPAuth = true;
            $mail->Username = 'apikey'; // Usuario SMTP (literalmente "apikey")
            $mail->Password = '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'; // Tu clave API de SendGrid
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Cifrado TLS
            $mail->Port = 587; // Puerto recomendado para TLS

            // Remitente y destinatario
            $mail->setFrom('juanmartinez@nusoft.com.co', 'Soporte educa.city'); // Cambia al email que usarás como remitente
            $mail->addAddress($this->email, $this->nombre);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Recuperar contraseña';
            $mail->Body = '<p><strong>Hola ' . $this->nombre . '</strong>,<br> Has solicitado reestablecer tu contraseña.</p>' .
                '<p>Presiona el siguiente enlace para recuperarla: <a href="http://localhost:3000/recuperar?token=' . $this->token . '">Recuperar contraseña</a></p>';

            // Enviar el correo
            $mail->send();
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    }
}















