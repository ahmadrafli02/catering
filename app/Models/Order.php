<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'event_date', 'event_time', 'venue', 'guest_count', 'status', 'notes', 'total_amount'
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'string',
        'guest_count' => 'integer',
        'total_amount' => 'decimal:2',
    ];

    public const STATUSES = [
        'draft', 'quoted', 'confirmed', 'preparing', 'delivered', 'completed', 'cancelled'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    protected function paidAmount(): Attribute
    {
        return Attribute::get(fn () => (string) $this->payments()->sum('amount'));
    }

    protected function balanceAmount(): Attribute
    {
        return Attribute::get(fn () => (string) ((float) $this->total_amount - (float) $this->paid_amount));
    }

    public function recalcTotals(): void
    {
        $this->total_amount = $this->items()->sum('total');
        $this->save();
    }
}
