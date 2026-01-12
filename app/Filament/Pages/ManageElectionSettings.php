<?php

namespace App\Filament\Pages;

use App\Models\ElectionSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class ManageElectionSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static ?string $title = 'Voting Period';

    protected string $view = 'filament.pages.manage-election-settings';

    /**
     * Store the form data.
     */
    public ?array $data = [];

    public function mount(): void
    {
        $settings = ElectionSetting::first();

        // Fill form with existing data or defaults
        $this->form->fill($settings ? $settings->attributesToArray() : []);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Voting Period Settings')
                    ->description('Atur tanggal mulai dan berakhirnya proses pemilihan. Pengguna hanya dapat memilih dalam jangka waktu ini.')
                    ->schema([
                        DateTimePicker::make('start_at')
                            ->label('Tanggal & Waktu Mulai')
                            ->required()
                            ->native(false)
                            ->displayFormat('F j, Y h:i A')
                            ->seconds(false)
                            ->hoursStep(1)
                            ->minutesStep(1)
                            ->timezone('Asia/Jakarta'),

                        DateTimePicker::make('end_at')
                            ->label('Tanggal & Waktu Berakhir')
                            ->required()
                            ->native(false)
                            ->displayFormat('F j, Y h:i A')
                            ->after('start_at')
                            ->seconds(false)
                            ->hoursStep(1)
                            ->minutesStep(1)
                            ->timezone('Asia/Jakarta'),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    /**
     * Define the save action/button.
     */
    public function save(): void
    {
        // Validate the form first
        $validated = $this->form->getState();

        try {
            DB::transaction(function () use ($validated) {
                // Update or Create the single record
                $settings = ElectionSetting::firstOrNew();
                $settings->start_at = $validated['start_at'];
                $settings->end_at = $validated['end_at'];
                $settings->save();
            });

            // Refresh the form with saved data
            $this->form->fill(ElectionSetting::first()->attributesToArray());

            Notification::make()
                ->title('Settings Saved Successfully')
                ->body('Voting period has been updated.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Saving Settings')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Helper to render the save button in the view via actions.
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
        ];
    }
}
