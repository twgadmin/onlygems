<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $table = "cards";
    public $timestamps = true;
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'internal_serial_number', 'name', 'grading_co', 'grading_co_serial_number', 'year', 'set', 'card', 'parralel', 'grade', 'category', 'description', 'image'
    ];


    public function cardPrices()
    {
        return $this->hasOne(CardPrice::class);
    }
}
