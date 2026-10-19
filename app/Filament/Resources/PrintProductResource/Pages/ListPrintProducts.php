<?php

namespace App\Filament\Resources\PrintProductResource\Pages;

use App\Filament\Resources\PrintProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrintProducts extends ListRecords
{
    protected static string $resource = PrintProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
