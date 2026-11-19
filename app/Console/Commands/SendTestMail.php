<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMail extends Command
{
    protected $signature = 'mail:test {email : Correo destino para probar el envío}';
    protected $description = 'Envía un correo de prueba para verificar la configuración SMTP en .env';

    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Intentando enviar correo de prueba a: {$email}...");

        try {
            Mail::raw("Este es un correo de prueba enviado desde Importadora Anturios para verificar que el servicio SMTP funciona correctamente.\n\nFecha y hora: " . now()->format('d/m/Y H:i:s'), function ($message) use ($email) {
                $message->to($email)
                        ->subject('Prueba de Conexión SMTP — Importadora Anturios');
            });

            $this->info("¡Éxito! El correo fue enviado satisfactoriamente usando el mailer: " . config('mail.default'));
        } catch (\Exception $e) {
            $this->error("Error al enviar el correo: " . $e->getMessage());
        }
    }
}

