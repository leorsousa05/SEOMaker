<?php

declare(strict_types=1);

namespace App\Core;

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
