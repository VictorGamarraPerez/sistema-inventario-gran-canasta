<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar correo de prueba con código de verificación';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $testCode = '123456';
        
        $this->info("Enviando correo de prueba a: {$email}");
        
        try {
            Mail::to($email)->send(new VerificationCodeMail($testCode, 'Usuario de Prueba'));
            $this->info("✅ Correo enviado exitosamente!");
            $this->info("Revisa la bandeja de entrada de: {$email}");
        } catch (\Exception $e) {
            $this->error("❌ Error al enviar correo:");
            $this->error($e->getMessage());
        }
    }
}
