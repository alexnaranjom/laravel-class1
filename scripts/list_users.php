<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = App\Models\User::select('id','name','email')->get();

if ($users->isEmpty()) {
    echo "No users found.\n";
    exit(0);
}

foreach ($users as $u) {
    printf("%d %s %s\n", $u->id, $u->name, $u->email);
}
