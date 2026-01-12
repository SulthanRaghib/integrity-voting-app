<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-user')
                    ->iconColor('primary')
                    ->label('Nama Lengkap'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('Email disalin!')
                    ->copyMessageDuration(1500)
                    ->label('Alamat Email'),

                IconColumn::make('email_verified_at')
                    ->label('Terverifikasi')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable()
                    ->alignCenter()
                    ->tooltip(fn($record): string => $record->email_verified_at
                        ? 'Terverifikasi pada ' . $record->email_verified_at->format('j M Y')
                        : 'Email belum terverifikasi'),

                TextColumn::make('created_at')
                    ->dateTime('j M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Terdaftar'),

                TextColumn::make('updated_at')
                    ->dateTime('j M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Terakhir Diperbarui'),
            ])
            ->filters([
                Filter::make('verified')
                    ->label('Email Terverifikasi')
                    ->query(fn($query) => $query->whereNotNull('email_verified_at')),

                Filter::make('unverified')
                    ->label('Email Belum Terverifikasi')
                    ->query(fn($query) => $query->whereNull('email_verified_at')),
            ])
            ->recordActions([
                ViewAction::make()->label('Lihat'),
                EditAction::make()->label('Edit'),
                DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Penghapusan')
                    ->modalDescription('Anda akan menghapus pengguna ini. Tindakan ini tidak dapat dibatalkan.')
                    ->modalSubmitActionLabel('Hapus')
                    ->before(function (DeleteAction $action, $record) {
                        // Check if user has cast any votes
                        if ($record->votes()->exists()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Tidak dapat menghapus pengguna')
                                ->body('Pengguna ini telah melakukan pemungutan suara dan tidak dapat dihapus untuk menjaga integritas pemilihan. Pengguna telah memberikan ' . $record->votes()->count() . ' suara.')
                                ->danger()
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Penghapusan')
                        ->modalDescription('Anda akan menghapus pengguna terpilih. Tindakan ini tidak dapat dibatalkan untuk pengguna yang belum memindahkan datanya.')
                        ->modalSubmitActionLabel('Hapus')
                        ->before(function (DeleteBulkAction $action, $records) {
                            // Check if any users have cast votes
                            $usersWithVotes = $records->filter(fn($record) => $record->votes()->exists());

                            if ($usersWithVotes->isNotEmpty()) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Tidak dapat menghapus pengguna')
                                    ->body($usersWithVotes->count() . ' pengguna telah melakukan pemungutan suara dan tidak dapat dihapus untuk menjaga integritas pemilihan.')
                                    ->danger()
                                    ->send();

                                $action->cancel();
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
