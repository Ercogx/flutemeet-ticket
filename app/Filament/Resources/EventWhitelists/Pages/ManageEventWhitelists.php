<?php

namespace App\Filament\Resources\EventWhitelists\Pages;

use App\Filament\Resources\EventWhitelists\EventWhitelistResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEventWhitelists extends ManageRecords
{
    protected static string $resource = EventWhitelistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
