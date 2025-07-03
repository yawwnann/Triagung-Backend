<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListShippedOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()?->where('status', 'shipped');
    }

    public static function getNavigationLabel(): string
    {
        return 'Dikirim';
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return true;
    }
}