<?php

namespace App\Filament\Resources\PrintOrderResource\Pages;

use App\Filament\Resources\PrintOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrintOrders extends ListRecords
{
    protected static string $resource = PrintOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
