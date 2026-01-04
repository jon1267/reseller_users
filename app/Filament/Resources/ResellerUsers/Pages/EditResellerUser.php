<?php

namespace App\Filament\Resources\ResellerUsers\Pages;

use App\Filament\Resources\ResellerUsers\ResellerUserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResellerUser extends EditRecord
{
    protected static string $resource = ResellerUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
