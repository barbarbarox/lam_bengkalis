<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
file_put_contents(storage_path('app/public/existing.png'), 'test');

class TestComp7 extends \Livewire\Component implements \Filament\Forms\Contracts\HasForms {
    use \Livewire\WithFileUploads, \Filament\Forms\Concerns\InteractsWithForms;
    public $logo_path = null;
    
    public function mount() {
        $this->form->fill(['logo_path' => 'existing.png']);
    }

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form {
        return $form->schema([
            \Filament\Forms\Components\FileUpload::make('logo_path')->disk('public')
        ])->statePath('');
    }

    public function getFormState() {
        return $this->form->getState();
    }
}

$comp = Livewire\Livewire::test(TestComp7::class);
echo "Prop value after mount: "; var_export($comp->instance()->logo_path); echo "\n";
echo "Form state: "; var_export($comp->instance()->getFormState()); echo "\n";
unlink(storage_path('app/public/existing.png'));
