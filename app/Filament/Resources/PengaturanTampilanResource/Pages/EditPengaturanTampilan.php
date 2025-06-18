<?php

namespace App\Filament\Resources\PengaturanTampilanResource\Pages;

use App\Filament\Resources\PengaturanTampilanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengaturanTampilan extends EditRecord
{
    protected static string $resource = PengaturanTampilanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
