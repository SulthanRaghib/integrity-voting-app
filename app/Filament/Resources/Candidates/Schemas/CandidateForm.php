<?php

namespace App\Filament\Resources\Candidates\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CandidateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('order_number')->numeric()->default(0),
                FileUpload::make('photo_path')->image()->directory('candidates')->disk('public')->required(),
                RichEditor::make('vision')->columnSpanFull(),
                RichEditor::make('mission')->columnSpanFull(),
            ]);
    }
}
