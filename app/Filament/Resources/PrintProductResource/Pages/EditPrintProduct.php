<?php

namespace App\Filament\Resources\PrintProductResource\Pages;

use App\Filament\Resources\PrintProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrintProduct extends EditRecord
{
    protected static string $resource = PrintProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
