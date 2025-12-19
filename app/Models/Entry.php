<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $supplier_id
 * @property int $product_id
 * @property int $quantity
 * @property \Carbon\Carbon $entry_date
 * @property string|null $observations
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Supplier $supplier
 * @property-read Product $product
 * @property-read User $user
 */
class Entry extends Model
{
    protected $fillable = [
        'supplier_id',
        'product_id',
        'document_type',
        'series',
        'number',
        'quantity',
        'total',
        'entry_date',
        'observations',
        'user_id'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'quantity' => 'integer',
        'total' => 'decimal:2'
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
