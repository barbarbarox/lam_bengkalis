<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$q = App\Models\Berita::query();
$closure = fn (\Illuminate\Database\Eloquent\Builder $q) => $q->where('status', 'published');
$q->where($closure);
echo $q->toSql();
