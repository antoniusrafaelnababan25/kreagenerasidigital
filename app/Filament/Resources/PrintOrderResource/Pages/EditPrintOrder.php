<?php

namespace App\Filament\Resources\PrintOrderResource\Pages;

use App\Filament\Resources\PrintOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrintOrder extends EditRecord
{
    protected static string $resource = PrintOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
