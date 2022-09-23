<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    /**
     * Fillable fields for Options.
     *
     * @var array
     */
    protected $fillable = [
        'variation_id',
        'option_value'
    ];

    public function variations()
    {
        return $this->belongsTo(Variation::class);
    }

    public function transactionItems() {
        return $this->hasMany(TransactionItem::class);
    }

    // public function products()
    // {
    //     return $this->belongsToMany(Product::class);
    // }
}
