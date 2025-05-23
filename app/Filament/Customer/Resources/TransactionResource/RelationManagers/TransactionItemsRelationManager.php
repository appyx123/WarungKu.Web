<?php

namespace App\Filament\Customer\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use App\Models\Product;
use App\Models\Transaction;

class TransactionItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactionItems';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Produk')
                    ->options(Product::where('stock', '>', 0)->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $product = Product::find($state);
                        $set('price', $product?->price ?? 0);
                        $this->calculateTotal($get, $set);
                    }),
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(fn ($get) => Product::find($get('product_id'))?->stock ?? 1)
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $this->calculateTotal($get, $set);
                    }),
                Forms\Components\TextInput::make('price')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->disabled()
                    ->dehydrated(true),
                Forms\Components\TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->disabled()
                    ->prefix('Rp')
                    ->default(0),
            ]);
    }

    protected function calculateTotal($get, $set): void
    {
        $quantity = $get('quantity') ?? 0;
        $price = $get('price') ?? 0;
        $set('subtotal', $quantity * $price);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Produk'),
                Tables\Columns\TextColumn::make('quantity')->label('Jumlah'),
                Tables\Columns\TextColumn::make('price')->label('Harga')->money('IDR'),
                Tables\Columns\TextColumn::make('subtotal')->label('Subtotal')->money('IDR'),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Item')
                    ->visible(fn ($livewire) => $livewire->ownerRecord->status === 'draft'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit')
                    ->visible(fn ($record) => $record->transaction->status === 'draft'),
                Tables\Actions\DeleteAction::make()->label('Hapus')
                    ->visible(fn ($record) => $record->transaction->status === 'draft'),
            ])
            ->bulkActions([]);
    }

    protected function afterCreate(): void
    {
        $this->updateTransactionTotal();
    }

    protected function afterEdit(): void
    {
        $this->updateTransactionTotal();
    }

    protected function afterDelete(): void
    {
        $this->updateTransactionTotal();
    }

    protected function updateTransactionTotal(): void
    {
        /** @var Transaction $transaction */
        $transaction = $this->getOwnerRecord();
        $transaction->updateTotalPrice();
    }
}
