<?php

namespace App\Filament\Resources\VideoOrderResource\Pages;

use App\Filament\Resources\VideoOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideoOrder extends EditRecord
{
    protected static string $resource = VideoOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
