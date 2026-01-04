<?php

namespace App\Filament\Resources\ResellerUsers\Pages;

use App\Filament\Resources\ResellerUsers\ResellerUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResellerUsers extends ListRecords
{
    protected static string $resource = ResellerUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
