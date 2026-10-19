<?php

namespace App\Filament\Resources\WebPackageResource\Pages;

use App\Filament\Resources\WebPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebPackage extends EditRecord
{
    protected static string $resource = WebPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
