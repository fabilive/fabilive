<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class SystemActivation extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static string $view = 'filament.pages.system-activation';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'System Activation';

    protected static ?int $navigationSort = 110;

    public ?string $pcode = '';

    public function mount()
    {
        if (File::exists(public_path('project/license.txt'))) {
            $this->pcode = File::get(public_path('project/license.txt'));
        }
    }

    public function getActivationStatusProperty(): string
    {
        if (File::exists(public_path('project/license.txt'))) {
            $license = File::get(public_path('project/license.txt'));
            if (!empty($license)) {
                return "Your System is Activated! License Key: " . $license;
            }
        }
        return "Your System is not Activated.";
    }

    public function activate()
    {
        $this->validate([
            'pcode' => 'required',
        ]);

        $my_script = 'eCommerceGenius Bundle';
        $my_domain = url('/');
        $purchase_code = $this->pcode;

        $baseUrl = config('services.genius.ocean');
        $varUrl = $baseUrl . 'purchase112662activate.php?code=' . $purchase_code . '&domain=' . $my_domain . '&script=' . $my_script;

        try {
            $response = Http::get($varUrl);
            $chk = $response->json();

            if ($chk['status'] != "success") {
                $msg = $chk['message'] ?? 'Purchase Code Invalid.';
                Notification::make()
                    ->title($msg)
                    ->danger()
                    ->send();
            } else {
                $this->setUp($chk['p2'], $chk['lData']);

                if (File::exists(public_path('rooted.txt'))) {
                    File::delete(public_path('rooted.txt'));
                }

                File::ensureDirectoryExists(public_path('project'));
                File::put(public_path('project/license.txt'), $purchase_code);

                Notification::make()
                    ->title('Congratulation!! Your System is successfully Activated.')
                    ->success()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error connecting to activation server.')
                ->danger()
                ->send();
        }
    }

    protected function setUp($mtFile, $goFileData)
    {
        File::put(public_path($mtFile), $goFileData);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Activation Code')
                    ->schema([
                        TextInput::make('pcode')
                            ->label('Purchase Code')
                            ->placeholder('Enter your purchase code')
                            ->required(),
                    ]),
            ]);
    }
}
