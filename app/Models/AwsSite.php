<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwsSite extends Model
{
    use HasFactory;
    protected $fillable = [
        'site_name'
    ];

    public function awssitepricelists() {
        return $this->hasMany(AwsSitePriceList::class);
    }
}
