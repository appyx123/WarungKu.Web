<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Builder;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $slug = 'transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'name')
                    ->disabled(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'checked_out' => 'Checked Out',
                        'cancelled' => 'Cancelled',
                    ])
                    ->disabled(),
                Forms\Components\TextInput::make('total_price')
                    ->label('Total Harga')
                    ->prefix('Rp')
                    ->disabled(),
                Forms\Components\Repeater::make('transactionItems')
                    ->relationship('transactionItems')
                    ->schema([
                        Forms\Components\TextInput::make('product.name')
                            ->label('Nama Produk')
                            ->disabled(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah')
                            ->disabled(),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga Satuan')
                            ->prefix('Rp')
                            ->disabled(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'checked_out'))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Transaksi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_code')
                    ->label('Kode Invoice')
                    ->getStateUsing(fn ($record) => 'INV-' . $record->created_at->format('Ymd') . '-' . str_pad($record->id, 3, '0', STR_PAD_LEFT))
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_items_count')
                    ->label('Jumlah Jenis Produk')
                    ->counts('transactionItems')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('download_pdf')
                    ->label('Unduh Invoice')
                    ->action(function ($record) {
                        $pdf = Pdf::loadView('pdf.invoice', ['transaction' => $record]);
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'invoice-transaction-' . $record->id . '.pdf');
                    })
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
        ];
    }
}
