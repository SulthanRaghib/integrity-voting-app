<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Pengguna')
                    ->description('Kelola informasi akun pengguna dan kredensial.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Lengkap')
                            ->placeholder('Budi Santoso'),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(User::class, 'email', ignoreRecord: true)
                            ->prefixIcon('heroicon-m-envelope')
                            ->label('Alamat Email')
                            ->placeholder('nama@contoh.com'),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->maxLength(255)
                            ->label('Kata Sandi')
                            ->placeholder('Masukkan kata sandi baru')
                            ->helperText(fn(string $operation): string => $operation === 'edit'
                                ? 'Biarkan kosong untuk mempertahankan kata sandi saat ini'
                                : 'Disarankan minimal 8 karakter'),

                        DateTimePicker::make('email_verified_at')
                            ->label('Email Terverifikasi')
                            ->nullable()
                            ->helperText('Atur ini untuk menandai email sebagai terverifikasi')
                            ->displayFormat('j M Y H:i')
                            ->timezone('Asia/Jakarta')
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }
}
