<?php

namespace App\Filament\Resources\ResellerUsers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;

class ResellerUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                //DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->hiddenOn(Operation::Edit),
                //TextInput::make('tenant_id')
                //    ->numeric(),
                Select::make('role')
                    ->required()
                    ->options([
                        'reseller_admin' => 'Admin',
                        'reseller_agent' => 'Agent'
                    ]),
                Select::make('currency')
                    ->options(config('currencies')),
                TextInput::make('country'),
                TextInput::make('city'),
                Select::make('language')
                    ->options(config('locales')),
                Toggle::make('is_active')
                    ->required(),
                DateTimePicker::make('last_login_at'),
            ]);
    }
}
