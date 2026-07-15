<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::find(1);
if (! $user->twoFactorAuth) {
    $user->createTwoFactorAuth();
    $user->load('twoFactorAuth');
}

$auth = $user->twoFactorAuth;
if (empty($auth->label)) {
    $auth->update(['label' => config('app.name') . ':' . $user->email]);
}

try {
    $qrCodeSvg = $auth->toQr()->toSvg();
    echo "QR SVG exists\n";
} catch (\Exception $e) {
    echo "QR error: " . $e->getMessage() . "\n";
}

$recoveryCodes = $user->getRecoveryCodes();
if (! $recoveryCodes || $recoveryCodes->isEmpty()) {
    $recoveryCodes = $user->generateRecoveryCodes();
}

var_dump($recoveryCodes->toArray());
