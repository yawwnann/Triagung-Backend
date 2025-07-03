<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Order;

class PesananDikirim extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Manajemen Pesanan';
    protected static ?string $navigationLabel = 'Dikirim';
    protected static ?string $title = 'Pesanan Dikirim';
    protected static string $view = 'filament.pages.pesanan-dikirim';

    protected function getTableQuery()
    {
        return Order::query()->where('status', 'shipped');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('order_number')->label('Nomor Pesanan')->searchable(),
            Tables\Columns\TextColumn::make('user.name')->label('Pelanggan')->searchable(),
            Tables\Columns\TextColumn::make('grand_total')->label('Total')->money('IDR')->sortable(),
            Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
            Tables\Columns\TextColumn::make('payment_status')->label('Pembayaran')->badge(),
            Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y H:i')->sortable(),
        ];
    }
}