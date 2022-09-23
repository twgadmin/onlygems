<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwsSitePriceList extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'aws_site_price_list';


    protected $fillable = [
        'product_id',
        'site_id',
        'price'
    ];

    protected $appends = [
        'siteName',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function site() {
        return $this->belongsTo(AwsSite::class);
    }

    public function getSiteNameAttribute() {
        return $this->site->site_name;
    }
}
