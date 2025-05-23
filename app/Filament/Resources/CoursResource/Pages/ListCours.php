<?php

namespace App\Filament\Resources\CoursResource\Pages;

use App\Filament\Resources\CoursResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCours extends ListRecords
{
    protected static string $resource = CoursResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
