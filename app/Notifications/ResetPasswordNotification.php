<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Recuperación de Contraseña — Importadora Anturios')
            ->greeting('¡Hola, ' . ($notifiable->name ?? 'Usuario') . '!')
            ->line('Recibiste este correo electrónico porque se ha solicitado un restablecimiento de contraseña para tu cuenta de acceso a Importadora Anturios.')
            ->action('Restablecer Contraseña', $resetUrl)
            ->line('Este enlace de recuperación caducará en ' . config('auth.passwords.users.expire', 60) . ' minutos.')
            ->line('Si no fuiste tú quien solicitó este cambio, puedes ignorar este mensaje con total tranquilidad; tu cuenta y contraseña actual permanecen seguras.')
            ->salutation("Atentamente,\nEquipo de Importadora Anturios");
    }
}

