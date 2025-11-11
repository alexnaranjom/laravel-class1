<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Full name')
                    ->helperText('Required. Max 255 characters.'),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->placeholder('alex@example.com')
                    ->helperText('Required, unique. Used to sign in.'),

                TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->nullable()
                    ->maxLength(30)
                    ->placeholder('+1 555 555 5555')
                    ->helperText('Optional. Max 30 characters.'),
            ]);
    }
}
