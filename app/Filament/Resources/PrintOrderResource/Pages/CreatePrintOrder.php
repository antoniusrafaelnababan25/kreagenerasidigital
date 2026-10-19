<?php

namespace App\Filament\Resources\PrintOrderResource\Pages;

use App\Filament\Resources\PrintOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePrintOrder extends CreateRecord
{
    protected static string $resource = PrintOrderResource::class;
}
