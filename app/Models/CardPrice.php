<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardPrice extends Model
{
    use HasFactory;
    protected $table = "card_prices";
    public $timestamps = true;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'card_id', 'price', 'closing_date', 'created_at', 'updated_at'
    ];


    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }
}
