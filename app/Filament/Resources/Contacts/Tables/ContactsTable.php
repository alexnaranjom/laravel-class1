<?php

namespace App\Filament\Resources\Contacts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use App\Models\User;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),

                TextColumn::make('phone'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('has_phone')
                    ->label('Has phone')
                    ->query(fn ($query) => $query->whereNotNull('phone')),

                Filter::make('no_phone')
                    ->label('No phone')
                    ->query(fn ($query) => $query->whereNull('phone')),
            ])
            // Enable bulk selection and provide a grouped bulk action for deleting selected records
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->label('Delete selected')
                    ->requiresConfirmation()
                    ->modalHeading('Delete selected contacts')
                    ->modalContent(fn () => 'Are you sure you want to delete the selected contacts? This action cannot be undone.')
                    ->successNotificationTitle('Contacts deleted')
                    ->visible(fn (?User $user) => $user?->is_admin ?? false),
            ])
            ->recordActions([
                Action::make('quick_email')
                    ->icon('heroicon-o-envelope')
                    ->url(fn ($record) => 'mailto:' . $record->email)
                    ->openUrlInNewTab(),

                ViewAction::make(),
                EditAction::make(),

                // Delete record action - only visible to admins
                Action::make('delete')
                    ->label('Delete')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->delete())
                    ->visible(fn (?User $user) => $user?->is_admin ?? false),
            ]);
    }
}
