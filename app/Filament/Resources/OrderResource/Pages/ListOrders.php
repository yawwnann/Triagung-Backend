<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Resources\Components\Tab;
use Filament\Actions;
use Filament\Tables\Filters\SelectFilter;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function getTabs(): array
    {
        return [
            'Diproses' => Tab::make('Diproses')
                ->modifyQueryUsing(fn($query) => $query->whereIn('status', ['processing', 'delivered'])),
            'Dikirim' => Tab::make('Dikirim')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'shipped')),
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

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->label('Status Pesanan')
                ->options([
                    'processing' => 'Diproses',
                    'shipped' => 'Dikirim',
                    'completed' => 'Berhasil',
                    'all' => 'Semua',
                ])
                ->default('all')
                ->query(function ($query, $value) {
                    if ($value !== 'all') {
                        $query->where('status', $value);
                    }
                }),
        ];
    }
}