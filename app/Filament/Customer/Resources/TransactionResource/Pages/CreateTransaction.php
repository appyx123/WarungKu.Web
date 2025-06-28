<?php

namespace App\Filament\Customer\Resources\TransactionResource\Pages;

use App\Filament\Customer\Resources\TransactionResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components;
use Illuminate\Support\Facades\Log;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getFormSchema(): array
    {
        return [
            Components\Select::make('product_id')
                ->label('Produk')
                ->options(\App\Models\Product::pluck('name', 'id'))
                ->required(),
            Components\TextInput::make('quantity')
                ->label('Jumlah')
                ->numeric()
                ->required()
                ->minValue(1),
            Components\TextInput::make('price')
                ->label('Harga Satuan')
                ->numeric()
                ->required(),
            Components\Select::make('status')
                ->label('Status')
                ->options([
                    'draft' => 'Draft',
                    'checked_out' => 'Selesai',
                ])
                ->default('draft')
                ->required(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('mutateFormDataBeforeCreate - Data awal:', $data);
        $data['customer_id'] = auth()->guard('customer')->id();
        $data['total_price'] = 0;
        Log::info('mutateFormDataBeforeCreate - Data final:', $data);
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $formState = $this->form->getRawState();
        Log::info('handleRecordCreation - Form State Lengkap:', $formState);
        $customerId = $data['customer_id'] ?? auth()->guard('customer')->id();
        $status = $data['status'] ?? 'draft';
        $productId = $formState['product_id'] ?? null;
        $quantity = $formState['quantity'] ?? null;
        $price = $formState['price'] ?? null;
        $totalPrice = 0;
        if ($quantity && $price) {
            $totalPrice = $quantity * $price;
        }

        Log::info('handleRecordCreation - Data transaksi:', [
            'customer_id' => $customerId,
            'status' => $status,
            'total_price' => $totalPrice,
        ]);
        $record = static::getModel()::create([
            'customer_id' => $customerId,
            'status' => $status,
            'total_price' => $totalPrice,
        ]);

        if ($productId && $quantity && $price) {
            $record->transactionItems()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ]);

            Log::info('Item berhasil disimpan:', [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        } else {
            Log::warning('Data item tidak lengkap untuk disimpan!', [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }

        $this->redirect('/customer/transaksi');
        return $record;
    }
}
