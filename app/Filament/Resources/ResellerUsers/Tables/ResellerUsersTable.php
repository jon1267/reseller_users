<?php

namespace App\Filament\Resources\ResellerUsers\Tables;

/*use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;*/

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ResellerUsersTable
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
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                //TextColumn::make('tenant_id')
                //    ->numeric()
                //    ->sortable(),
                TextColumn::make('role')
                    ->searchable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('country')
                    ->searchable(),
                TextColumn::make('city')
                    ->searchable(),
                TextColumn::make('language')
                    ->label('Lang')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->searchable()
                    ->label('Status')
                    ->boolean(),
                TextColumn::make('last_login_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('Is_Active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
                Filter::make('Not_Active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', false)),
                Filter::make('Reseller_Agent')
                    ->query(fn (Builder $query): Builder => $query->where('role', '=' ,'reseller_agent')),
                Filter::make('Reseller_Admin')
                    ->query(fn (Builder $query): Builder => $query->where('role', '=' ,'reseller_admin')),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
                EditAction::make(),

                Action::make('resetPassword')
                    ->label('Reset Password')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->hidden(fn ($record): bool =>
                        Auth::user()->role !== 'reseller_admin' // Hide if user is not admin
                    )
                    ->schema([
                        TextInput::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->rule('min:5')
                            ->confirmed(), // Requires a matching 'new_password_confirmation' field
                        TextInput::make('new_password_confirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->required(),
                    ])
                    ->action(function (array $data, \App\Models\User $record): void {
                        $record->forceFill([
                            'password' => Hash::make($data['new_password']),
                        ])->save();

                        Notification::make()
                            ->title('Password reset successfully.')
                            ->success()
                            ->send();
                    }),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])
                ->hidden(fn ($record): bool =>
                    Auth::user()->role !== 'reseller_admin' // Hide if user is not admin
                ),
            ]);
    }
}
