<?php

namespace App\Filament\Resources\VideoPackageResource\Pages;

use App\Filament\Resources\VideoPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideoPackage extends EditRecord
{
    protected static string $resource = VideoPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
