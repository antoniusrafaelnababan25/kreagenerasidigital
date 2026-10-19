<?php

namespace App\Filament\Resources\VideoOrderResource\Pages;

use App\Filament\Resources\VideoOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVideoOrders extends ListRecords
{
    protected static string $resource = VideoOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
