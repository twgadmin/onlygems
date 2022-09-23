<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'product_name',
        'brand_name',
        'description',
        'tags',
        'product_type',
        'sku_code_type',
        'sku_code',
        'aws_date',
        'aws_category',
        'aws_source',
        'aws_term',
        'aws_itemid',
        'aws_price'
    ];


    public function variations()
    {
        return $this->belongsToMany(Variation::class)->withPivot('option_id','qty','cost_price','total_price');
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'product_variation', 'product_id', 'option_id')->withPivot('option_id','qty','cost_price','total_price');
    }

    public function transactions() {
        return $this->belongsToMany(Transaction::class);
    }

    public function awsSitePriceLists() {
        return $this->hasMany(AwsSitePriceList::class);
    }
}
