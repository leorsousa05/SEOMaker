<?php

declare(strict_types=1);

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function send(string $to, string $subject, string $body, ?string $template = null): bool
    {
        $from = Config::get('mail_from', 'noreply@example.com');
        $fromName = Config::get('mail_from_name', 'Site');
        
        if ($template !== null) {
            $templatePath = __DIR__ . '/../../templates/emails/' . $template . '.php';
            if (file_exists($templatePath)) {
                ob_start();
                extract(['body' => $body, 'subject' => $subject]);
                require $templatePath;
                $body = ob_get_clean();
            }
        }
        
        $smtpHost = Config::get('smtp_host', '');
        $smtpPort = (int) Config::get('smtp_port', 0);
        $smtpUser = Config::get('smtp_user', '');
        $smtpPass = Config::get('smtp_pass', '');
        
        $hasSmtp = $smtpHost !== '' && $smtpPort > 0 && $smtpUser !== '';
        
        if ($hasSmtp && class_exists(PHPMailer::class)) {
            return self::sendWithSmtp($to, $subject, $body, $from, $fromName, $smtpHost, $smtpPort, $smtpUser, $smtpPass);
        }
        
        return self::sendWithNativeMail($to, $subject, $body, $from, $fromName);
    }
    
    private static function sendWithSmtp(
        string $to,
        string $subject,
        string $body,
        string $from,
        string $fromName,
        string $smtpHost,
        int $smtpPort,
        string $smtpUser,
        string $smtpPass
    ): bool {
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = $smtpHost;
            $mail->Port = $smtpPort;
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUser;
            $mail->Password = $smtpPass;
            
            if ($smtpPort === 465) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($smtpPort === 587) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $mail->setFrom($from, $fromName);
            $mail->addAddress($to);
            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = $body;
            
            return $mail->send();
        } catch (Exception $e) {
            error_log('PHPMailer error: ' . $e->getMessage());
            return false;
        }
    }
    
    private static function sendWithNativeMail(string $to, string $subject, string $body, string $from, string $fromName): bool
    {
        $headers = [
            'From' => "{$fromName} <{$from}>",
            'Reply-To' => $from,
            'X-Mailer' => 'PHP/' . phpversion(),
            'Content-Type' => 'text/html; charset=UTF-8',
        ];
        
        $headerString = '';
        foreach ($headers as $key => $value) {
            $headerString .= "{$key}: {$value}\r\n";
        }
        
        return mail($to, $subject, $body, $headerString);
    }
}
