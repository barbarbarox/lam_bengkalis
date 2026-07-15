<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

class TestComp2 extends \Livewire\Component {
    use \Livewire\WithFileUploads;
    public $logo_path = [];
    public function render() { return '<div></div>'; }
}

$comp = new TestComp2();
try {
    Livewire\Livewire::test(TestComp2::class)
        ->call('_finishUpload', 'logo_path.uuid', ['/tmp/file.png'], false, false);
    echo "SUCCESS with array\n";
} catch (\Exception $e) {
    echo "ERROR with array: " . $e->getMessage() . "\n";
}

class TestComp3 extends \Livewire\Component {
    use \Livewire\WithFileUploads;
    public $logo_path = '';
    public function render() { return '<div></div>'; }
}

try {
    Livewire\Livewire::test(TestComp3::class)
        ->call('_finishUpload', 'logo_path.uuid', ['/tmp/file.png'], false, false);
    echo "SUCCESS with string\n";
} catch (\Exception $e) {
    echo "ERROR with string: " . $e->getMessage() . "\n";
}
