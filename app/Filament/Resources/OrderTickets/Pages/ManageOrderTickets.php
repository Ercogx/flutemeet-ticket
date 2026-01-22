<?php

namespace App\Filament\Resources\OrderTickets\Pages;

use App\Filament\Resources\OrderTickets\OrderTicketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageOrderTickets extends ManageRecords
{
    protected static string $resource = OrderTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
