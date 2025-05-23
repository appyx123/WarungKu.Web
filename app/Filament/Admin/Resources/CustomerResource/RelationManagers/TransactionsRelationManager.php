<?php

namespace App\Filament\Admin\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Transaksi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'warning',
                        'checked_out' => 'success',
                        'cancelled' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'checked_out' => 'Checked Out',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('Filter Status'),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('view_invoice')
                    ->label('Lihat Invoice')
                    ->icon('heroicon-o-document-text')
                    ->action(function ($record) {
                        $pdf = Pdf::loadHTML(Blade::render('pdf.invoice', ['transaction' => $record]));
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'invoice-' . $record->id . '.pdf'
                        );
                    }),
                Tables\Actions\ViewAction::make()->label('Detail')
                    ->form([
                        Forms\Components\TextInput::make('id')
                            ->label('ID Transaksi')
                            ->disabled(),
                        Forms\Components\TextInput::make('status')
                            ->label('Status')
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
                    ]),
            ])
            ->bulkActions([]);
    }
}
