<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

class TestComp8 extends \Livewire\Component implements \Filament\Forms\Contracts\HasForms {
    use \Livewire\WithFileUploads, \Filament\Forms\Concerns\InteractsWithForms;
    public $logo_path = null;
    
    public function mount() {
        $this->form->fill(['logo_path' => null]);
    }

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form {
        return $form->schema([
            \Filament\Forms\Components\FileUpload::make('logo_path')
        ])->statePath('');
    }

    public function render() { return '<div></div>'; }
}

$comp = Livewire\Livewire::test(TestComp8::class);
echo "Prop value after fill null: "; var_export($comp->instance()->logo_path); echo "\n";
