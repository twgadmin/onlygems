<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'transaction_id',
        'product_id',
        'variation_id',
        'option_id',
        'product_name',
        'qty',
        'cost_price',
        'total_cost',
        'created_at',
        'updated_at'
    ];

    public function transactions()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function options() {
        return $this->belongsTo(Option::class, 'option_id', 'id');
    }

    public function variations() {
        return $this->belongsTo(Variation::class, 'variation_id', 'id');
    }
}
