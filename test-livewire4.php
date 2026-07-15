<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

class TestComp4 extends \Livewire\Component {
    use \Livewire\WithFileUploads;
    public ?array $data = [];
    
    public function mount() {
        $this->data['logo_path'] = 'existing-logo.png';
    }
    public function render() { return '<div></div>'; }
}

try {
    Livewire\Livewire::test(TestComp4::class)
        ->call('_finishUpload', 'data.logo_path.uuid', ['/tmp/file.png'], false, false);
    echo "SUCCESS with data array\n";
} catch (\Exception $e) {
    echo "ERROR with data array: " . $e->getMessage() . "\n";
}
