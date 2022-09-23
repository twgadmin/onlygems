<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    /**
     * Fillable fields for Variations.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'id'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function transactionItems() {
        return $this->hasMany(TransactionItem::class);
    }
}
