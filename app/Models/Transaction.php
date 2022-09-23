<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'supplier_id',
        'supplier_invoice_number',
        'delivery_date',
        'order_number',
        'delivery_notes',
    ];


    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
}
