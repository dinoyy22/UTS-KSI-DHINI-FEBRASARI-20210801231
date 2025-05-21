<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function canEdit(Model $record): bool
    {
        // Allow users to edit only their own profile, but admins can edit any profile
        return $record->id === Auth::id() || (Auth::user() && Auth::user()->is_admin);
    }
}
