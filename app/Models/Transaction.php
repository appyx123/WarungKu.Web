<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'status',
        'total_price',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relasi: Transaction milik satu Customer
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi: Transaction memiliki banyak TransactionItem
    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

  public function updateTotalPrice(): void
{
    $this->total_price = $this->transactionItems()->sum(\DB::raw('quantity * price'));
    $this->save();
}

}
