<?php

namespace App\Filament\Resources\ResellerUsers\Pages;

use App\Filament\Resources\ResellerUsers\ResellerUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResellerUser extends CreateRecord
{
    protected static string $resource = ResellerUserResource::class;
}
