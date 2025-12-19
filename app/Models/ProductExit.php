<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property string $reason
 * @property \Carbon\Carbon $exit_date
 * @property string|null $observations
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Product $product
 * @property-read User $user
 */
class ProductExit extends Model
{
    protected $table = 'exits';
    
    protected $fillable = [
        'product_id',
        'quantity',
        'reason',
        'exit_date',
        'observations',
        'user_id'
    ];

    protected $casts = [
        'exit_date' => 'date',
        'quantity' => 'integer'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
