<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

class TestComp6 extends \Livewire\Component implements \Filament\Forms\Contracts\HasForms {
    use \Livewire\WithFileUploads, \Filament\Forms\Concerns\InteractsWithForms;
    public $logo_path = null;
    
    public function mount() {
        $this->form->fill(['logo_path' => 'existing.png']);
    }

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form {
        return $form->schema([
            \Filament\Forms\Components\FileUpload::make('logo_path')
        ])->statePath('');
    }

    public function getFormState() {
        return $this->form->getState();
    }
    public function render() { return '<div></div>'; }
}

$comp = Livewire\Livewire::test(TestComp6::class);
echo "Prop value after mount: "; var_export($comp->instance()->logo_path); echo "\n";
echo "Form state: "; var_export($comp->instance()->getFormState()); echo "\n";

