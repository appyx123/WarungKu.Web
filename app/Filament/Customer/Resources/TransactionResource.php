<?php

namespace App\Filament\Customer\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use App\Models\Transaction;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Filament\Customer\Resources\TransactionResource\Pages;
use App\Filament\Customer\Resources\TransactionResource\RelationManagers\TransactionItemsRelationManager;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $slug = 'transaksi';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'checked_out' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->default('draft')
                    ->disabled(fn ($record) => $record && $record->status !== 'draft')
                    ->required(),
                Forms\Components\TextInput::make('total_price')
                    ->label('Total Harga')
                    ->numeric()
                    ->disabled()
                    ->prefix('Rp')
                    ->default(0),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Kode Invoice'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft' => 'Draft',
                        'checked_out' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    }),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'checked_out' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['value'])) {
                            $query->where('status', $data['value']);
                        } else {
                            $query->whereIn('status', ['checked_out', 'cancelled']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit')
                    ->visible(fn ($record) => $record->status === 'draft'),
                Tables\Actions\Action::make('checkout')
                    ->label('Checkout')
                    ->action(function ($record) {
                        $record->update(['status' => 'checked_out']);
                    })
                    ->visible(fn ($record) => $record->status === 'draft')
                    ->requiresConfirmation()
                    ->color('success'),
                Tables\Actions\Action::make('cancel')
                    ->label('Batalkan')
                    ->action(function ($record) {
                        $record->update(['status' => 'cancelled']);
                    })
                    ->visible(fn ($record) => $record->status === 'checked_out')
                    ->requiresConfirmation()
                    ->color('danger'),
                Tables\Actions\Action::make('download_pdf')
                    ->label('Unduh Invoice')
                    ->action(function ($record) {
                        $pdf = Pdf::loadView('pdf.invoice', ['transaction' => $record]);
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'invoice-transaction-' . $record->id . '.pdf');
                    })
                    ->visible(fn ($record) => $record->status === 'checked_out'),
                Tables\Actions\DeleteAction::make()->label('Hapus')
                    ->visible(fn ($record) => $record->status === 'draft'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            TransactionItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('customer_id', auth()->guard('customer')->id());
    }
}
