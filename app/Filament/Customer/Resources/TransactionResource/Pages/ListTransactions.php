<?php

namespace App\Filament\Customer\Resources\TransactionResource\Pages;

use App\Filament\Customer\Resources\TransactionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Transaksi Baru'),
        ];
    }

    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return TransactionResource::getEloquentQuery()
            ->whereIn('status', ['checked_out', 'cancelled']);
    }
}
