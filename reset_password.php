<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::find(2);

echo "Usuario encontrado: {$user->name} ({$user->email})\n\n";
echo "Ingresa la nueva contraseña: ";
$password = trim(fgets(STDIN));

$user->password = \Illuminate\Support\Facades\Hash::make($password);
$user->save();

echo "\n✅ Contraseña actualizada exitosamente!\n";
echo "\nAhora puedes iniciar sesión con:\n";
echo "Email: {$user->email}\n";
echo "Contraseña: {$password}\n";
