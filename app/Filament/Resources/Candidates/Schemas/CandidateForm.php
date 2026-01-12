<?php

namespace App\Filament\Resources\Candidates\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class CandidateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Candidate Details')
                    ->tabs([
                        // Tab 1: Profil Pribadi
                        Tabs\Tab::make('Profile')
                            ->icon('heroicon-o-user')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('order_number')
                                    ->label('Nomor Urut')
                                    ->numeric()
                                    ->default(0),
                                FileUpload::make('photo_path')
                                    ->label('Foto Kandidat')
                                    ->image()
                                    ->directory('candidates')
                                    ->disk('public')
                                    ->required()
                                    ->columnSpanFull(),

                                \Filament\Schemas\Components\Grid::make(2)
                                    ->schema([
                                        TextInput::make('birth_place')
                                            ->label('Tempat Lahir')
                                            ->maxLength(255),
                                        DatePicker::make('birth_date')
                                            ->label('Tanggal Lahir'),
                                    ]),

                                TextInput::make('occupation')
                                    ->label('Pekerjaan Saat Ini')
                                    ->maxLength(255),

                                Textarea::make('address')
                                    ->label('Alamat Domisili')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),

                        // Tab 2: Visi & Misi
                        Tabs\Tab::make('Platform')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                RichEditor::make('vision')
                                    ->label('Visi')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'bulletList',
                                        'orderedList',
                                        'redo',
                                        'undo',
                                    ])
                                    ->columnSpanFull(),
                                RichEditor::make('mission')
                                    ->label('Misi')
                                    ->columnSpanFull(),
                            ]),

                        // Tab 3: Curriculum Vitae
                        Tabs\Tab::make('Curriculum Vitae')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                RichEditor::make('education_history')
                                    ->label('Riwayat Pendidikan')
                                    ->placeholder('Contoh: S1 Teknik Informatika - Universitas X (2010-2014)')
                                    ->columnSpanFull(),
                                RichEditor::make('organization_experience')
                                    ->label('Pengalaman Organisasi')
                                    ->placeholder('Contoh: Ketua Himpunan Mahasiswa (2012)')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
