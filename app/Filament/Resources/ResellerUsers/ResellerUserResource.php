<?php

namespace App\Filament\Resources\ResellerUsers;

use App\Filament\Resources\ResellerUsers\Pages\CreateResellerUser;
use App\Filament\Resources\ResellerUsers\Pages\EditResellerUser;
use App\Filament\Resources\ResellerUsers\Pages\ListResellerUsers;
use App\Filament\Resources\ResellerUsers\Schemas\ResellerUserForm;
use App\Filament\Resources\ResellerUsers\Tables\ResellerUsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ResellerUserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Reseller User';
    protected static ?string $modelLabel = 'Reseller User';
    protected static ?string $pluralModelLabel = 'Reseller Users';

    //protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $tenantOwnershipRelationshipName = 'teams';

    public static function form(Schema $schema): Schema
    {
        return ResellerUserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResellerUsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResellerUsers::route('/'),
            'create' => CreateResellerUser::route('/create'),
            'edit' => EditResellerUser::route('/{record}/edit'),
        ];
    }
}
