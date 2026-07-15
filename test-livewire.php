<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

class TestComp extends \Livewire\Component {
    use \Livewire\WithFileUploads;
    public $logo_path = null;
    public function render() { return '<div></div>'; }
}

$comp = new TestComp();
try {
    Livewire\Livewire::test(TestComp::class)
        ->call('_finishUpload', 'logo_path.uuid', ['/tmp/file.png'], false, false);
    echo "SUCCESS with null\n";
} catch (\Exception $e) {
    echo "ERROR with null: " . $e->getMessage() . "\n";
}
