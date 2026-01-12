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
                Section::make('Election Duration')
                    ->description('Set the start and end dates for the voting process. Users can only vote within this window.')
                    ->schema([
                        DateTimePicker::make('start_at')
                            ->label('Start Date & Time')
                            ->required()
                            ->native(false) // Better UX
                            ->seconds(false),

                        DateTimePicker::make('end_at')
                            ->label('End Date & Time')
                            ->required()
                            ->after('start_at')
                            ->native(false)
                            ->seconds(false),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    /**
     * Define the save action/button.
     */
    public function save(): void
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {
            // Update or Create the single record
            $settings = ElectionSetting::firstOrNew();
            $settings->fill($data);
            $settings->save();
        });

        Notification::make()
            ->title('Settings Saved')
            ->success()
            ->send();
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
