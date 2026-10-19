<?php

namespace App\Filament\Resources\WebOrderResource\Pages;

use App\Filament\Resources\WebOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebOrder extends EditRecord
{
    protected static string $resource = WebOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
