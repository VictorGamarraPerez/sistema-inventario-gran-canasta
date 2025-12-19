<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Ingresa el nuevo correo Gmail: ";
$newEmail = trim(fgets(STDIN));

$user = \App\Models\User::find(2);
$user->email = $newEmail;
$user->save();

echo "\n✅ Email actualizado a: {$newEmail}\n";
echo "Ahora puedes iniciar sesión con este correo y recibirás los códigos de verificación.\n";
