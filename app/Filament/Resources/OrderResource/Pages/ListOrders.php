<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Resources\Components\Tab;
use Filament\Actions;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function getTabs(): array
    {
        return [
            'Diproses' => Tab::make('Diproses')
                ->modifyQueryUsing(fn($query) => $query->whereIn('status', ['processing', 'shipped', 'delivered'])),
            'Berhasil' => Tab::make('Berhasil')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'completed')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}