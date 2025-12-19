<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = \App\Models\User::all(['id', 'name', 'email', 'role']);

echo "USUARIOS EN EL SISTEMA:\n";
echo str_repeat("-", 80) . "\n";
echo sprintf("%-5s | %-30s | %-30s | %-15s\n", "ID", "Nombre", "Email", "Rol");
echo str_repeat("-", 80) . "\n";

foreach ($users as $user) {
    echo sprintf("%-5s | %-30s | %-30s | %-15s\n", 
        $user->id, 
        substr($user->name, 0, 30), 
        substr($user->email, 0, 30), 
        $user->role
    );
}

echo str_repeat("-", 80) . "\n";
