<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

class TestComp9 extends \Livewire\Component implements \Filament\Forms\Contracts\HasForms {
    use \Livewire\WithFileUploads, \Filament\Forms\Concerns\InteractsWithForms;
    public array $slides = [];
    
    public function mount() {
        $this->form->fill([
            'slides' => [
                ['image_path' => 'test.png']
            ]
        ]);
    }

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form {
        return $form->schema([
            \Filament\Forms\Components\Repeater::make('slides')->schema([
                \Filament\Forms\Components\FileUpload::make('image_path')
            ])
        ])->statePath('');
    }

    public function getFormState() {
        return $this->form->getState();
    }
}

$comp = Livewire\Livewire::test(TestComp9::class);
echo "Prop value: "; var_export($comp->instance()->slides); echo "\n";
echo "Form state: "; var_export($comp->instance()->getFormState()); echo "\n";
