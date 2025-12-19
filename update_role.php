<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::find(2);
$user->role = 'administrador';
$user->save();

echo "✅ Rol actualizado!\n\n";
echo "Usuario: {$user->name}\n";
echo "Email:   {$user->email}\n";
echo "Rol:     Administrador\n";
