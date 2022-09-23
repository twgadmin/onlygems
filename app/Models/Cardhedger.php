<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cardhedger extends Model
{
    use HasFactory;

    protected $table = "cardhedger";
    public $timestamps = true;
    protected $guarded = [
        'id',
    ];

   protected $fillable = [
        'name','grade','price','card_desc','card_number','card_variant','sale_date','category'
    ];

}
