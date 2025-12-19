<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::find(2);

$password = 'admin123';
$user->password = \Illuminate\Support\Facades\Hash::make($password);
$user->save();

echo "✅ Contraseña actualizada!\n\n";
echo "Ahora inicia sesión con:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Email:      victorgp2098@gmail.com\n";
echo "Contraseña: admin123\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
