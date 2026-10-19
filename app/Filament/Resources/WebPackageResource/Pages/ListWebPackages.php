<?php

namespace App\Filament\Resources\WebPackageResource\Pages;

use App\Filament\Resources\WebPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebPackages extends ListRecords
{
    protected static string $resource = WebPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
