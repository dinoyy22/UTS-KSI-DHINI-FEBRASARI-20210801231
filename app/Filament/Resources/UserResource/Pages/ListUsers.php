<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        // Only show create action to admins
        return Auth::user() && Auth::user()->is_admin
            ? [Actions\CreateAction::make()]
            : [];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        // Non-admin users can only see their own record
        if (!(Auth::user() && Auth::user()->is_admin)) {
            $query->where('id', Auth::id());
        }

        return $query;
    }
}
